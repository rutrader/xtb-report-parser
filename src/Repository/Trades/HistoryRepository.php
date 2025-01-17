<?php

namespace App\Repository\Trades;

use App\Entity\Trades\History;
use App\Service\TradesHistoryService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method History|null find($id, $lockMode = null, $lockVersion = null)
 * @method History|null findOneBy(array $criteria, array $orderBy = null)
 * @method History[]    findAll()
 * @method History[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HistoryRepository extends ServiceEntityRepository
{
	
	/**
	 * @param \Doctrine\Persistence\ManagerRegistry $registry [description]
	 */
	public function __construct(ManagerRegistry $registry)
	{
		parent::__construct($registry, History::class);
	}
	
	/**
	 * @throws ORMException
	 * @throws OptimisticLockException
	 */
	public function add(History $entity, bool $flush = true): void
	{
		$this->_em->persist($entity);
		if ($flush) {
			$this->_em->flush();
		}
	}
	
	/**
	 * @throws ORMException
	 * @throws OptimisticLockException
	 */
	public function remove(History $entity, bool $flush = true): void
	{
		$this->_em->remove($entity);
		if ($flush) {
			$this->_em->flush();
		}
	}
	
	/**
	 * @param \Symfony\Component\Security\Core\User\UserInterface $user
	 * @param string $period
	 * @return array
	 */
	public function getTradesResults(UserInterface $user, string $period): array
	{
		$qb = $this->createQueryBuilder('h');
		
		switch ($period) {
			case TradesHistoryService::BY_MONTH:
				$qb->select('MONTH(DATE_TRUNC(:period, h.openedAt)) as trade_date');
				break;
			case TradesHistoryService::BY_QUARTER:
//				$qb->select('EXTRACT(QUARTER FROM DATE_TRUNC(:period, h.openedAt)) as trade_date');
				break;
			default:
				$qb->select('DATE_TRUNC(:period, h.openedAt) as trade_date');
				break;
		}
		
		$qb
			->addSelect('SUM(h.netProfit) as net_profit');
		
		$qb->where('h.trader = :trader');
		
		$qb
			->setParameter('period', $period)
			->setParameter('trader', $user)
		;
		
		$qb
			->groupBy('trade_date');
		
		$qb->orderBy('trade_date', 'ASC');
		
		return $qb->getQuery()->getResult();
	}
	
	/**
	 * @var \Symfony\Component\Security\Core\User\UserInterface $user
	 * @return int|mixed|string|null
	 * @throws \Doctrine\ORM\NonUniqueResultException
	 */
	public function getOverallStats(UserInterface $user)
	{
		$qb = $this->createQueryBuilder('h');
		
		$qb
			->select('COALESCE(SUM(CASE WHEN h.netProfit >= 0 THEN 1 ELSE 0 END), 0) as profit_orders')
			->addSelect('COALESCE(SUM(CASE WHEN h.netProfit < 0 THEN 1 ELSE 0 END), 0) as loss_orders')
			->addSelect('COALESCE(SUM(CASE WHEN LOWER(order_type.alias) like :buy THEN 1 ELSE 0 END), 0) as buy_orders')
			->addSelect('COALESCE(SUM(CASE WHEN LOWER(order_type.alias) like :sell THEN 1 ELSE 0 END), 0) as sell_orders')
			->addSelect('COALESCE(SUM(h.netProfit), 0) as pl')
			->addSelect('COALESCE(COUNT(h.id), 0) as total_orders');
		
		$qb->join('h.orderType', 'order_type');
		
		$qb->andWhere('h.trader = :trader');
		
		
		$qb
			->setParameter('buy', 'buy%')
			->setParameter('sell', 'sell%')
			->setParameter('trader', $user);
		
		return $qb->getQuery()->getOneOrNullResult();
	}
	
	/**
	 * @param \Symfony\Component\Security\Core\User\UserInterface $user
	 * @return array
	 */
	public function statsByHours(UserInterface $user): array
	{
		$qb = $this->createQueryBuilder('h');
		
		$qb
			->select('MONTH(DATE_TRUNC(\'month\', h.openedAt)) as month')
			->addSelect('CAST(DATE_TRUNC(\'hour\', h.openedAt) as time) as time_start')
			->addSelect('CAST(DATE_TRUNC(\'hour\', DATE_ADD(h.openedAt, 1, \'hour\')) as time) as time_end')
//			->addSelect('CONCAT(CAST(DATE_TRUNC(\'hour\', h.openedAt) as time), \'-\', CAST(DATE_TRUNC(\'hour\', DATE_ADD(h.openedAt, 1, \'hour\')) as time)) as range')
			->addSelect('SUM(h.netProfit) as profit')
			->addSelect('COUNT(h.id) as trade_counter')
			->addSelect('SUM(CASE WHEN h.netProfit >= 0 THEN 1 ELSE 0 END) as winners')
			->addSelect('SUM(CASE WHEN h.netProfit < 0 THEN 1 ELSE 0 END) as losers');
		
		$qb->andWhere('h.trader = :trader');
		
		$qb->setParameter('trader', $user);
		
		$qb
			->addOrderBy('month', 'ASC')
			->addOrderBy('time_start');
		
		$qb
			->groupBy('month')
			->addGroupBy('time_start')
			->addGroupBy('time_end');
		
		return $qb->getQuery()->getResult();
	}
	
	/**
	 * @param \Symfony\Component\Security\Core\User\UserInterface $user
	 * @return array
	 */
	public function statsByDays(UserInterface $user): array
	{
		$qb = $this->createQueryBuilder('h');
		
		$qb
			->select('MONTH(DATE_TRUNC(\'month\', h.openedAt)) as month')
			->addSelect('CAST(DATE_TRUNC(\'day\', h.openedAt) as date) as trade_day')
			->addSelect('SUM(h.netProfit) as profit')
			->addSelect('COUNT(h.id) as trade_counter')
			->addSelect('SUM(CASE WHEN h.netProfit >= 0 THEN 1 ELSE 0 END) as winners')
			->addSelect('SUM(CASE WHEN h.netProfit < 0 THEN 1 ELSE 0 END) as losers');
		
		$qb->where('h.trader = :trader');
		
		$qb->setParameter('trader', $user);
		
		$qb
			->addOrderBy('month', 'ASC')
			->addOrderBy('trade_day');
		
		$qb
			->groupBy('month')
			->addGroupBy('trade_day');
		
		return $qb->getQuery()->getResult();
	}
	
	/**
	 * @return array
	 */
	public function statsByMonths(UserInterface $user): array
	{
		$qb = $this->createQueryBuilder('h');
		
		$qb
			->select('MONTH(DATE_TRUNC(\'month\', h.openedAt)) as month')
			->addSelect('SUM(h.netProfit) as profit')
			->addSelect('COUNT(h.id) as trade_counter')
			->addSelect('SUM(CASE WHEN h.netProfit >= 0 THEN 1 ELSE 0 END) as winners')
			->addSelect('SUM(CASE WHEN h.netProfit < 0 THEN 1 ELSE 0 END) as losers');
		
		$qb->where('h.trader = :trader');
		
		$qb->setParameter('trader', $user);
		
		$qb->addOrderBy('month', 'ASC');
		
		$qb->groupBy('month');
		
		return $qb->getQuery()->getResult();
	}
	
	/**
	 * @return int|mixed|string
	 */
	public function statsByMarkets(UserInterface $user)
	{
		$qb = $this->createQueryBuilder('h');
		
		$qb
			->select('MONTH(DATE_TRUNC(\'month\', h.openedAt)) as month')
			->addSelect('SUM(h.netProfit) as profit')
			->addSelect('market_type.name as market')
			->addSelect('COUNT(market_type.id) as market_counter')
			->addSelect('SUM(CASE WHEN h.netProfit >= 0 THEN 1 ELSE 0 END) as winners')
			->addSelect('SUM(CASE WHEN h.netProfit < 0 THEN 1 ELSE 0 END) as losers');
		
		$qb->join('h.marketType', 'market_type');
		
		$qb->where('h.trader = :trader');
		
		$qb->setParameter('trader', $user);
		
		$qb
			->groupBy('market')
			->addGroupBy('month');
		
		$qb->orderBy('month');
		
		return $qb->getQuery()->getResult();
	}
	
	/**
	 * @param \Symfony\Component\Security\Core\User\UserInterface $user
	 * @return int|mixed|string
	 */
	public function removeAll(UserInterface $user)
	{
		return $this->createQueryBuilder('h')
			->delete()
			->andWhere('h.trader = :user')
			->setParameter('user', $user)
			->getQuery()->execute();
	}
	
	/**
	 * @return int|mixed|string
	 */
	public function statsByAssets(UserInterface $user)
	{
		$qb = $this->createQueryBuilder('h');
		
		$qb
			->select('MONTH(DATE_TRUNC(\'month\', h.openedAt)) as month')
			->addSelect('h.symbol')
			->addSelect('SUM(h.netProfit) as profit')
			// ->addSelect('market_type.name as market')
			->addSelect('COUNT(h.symbol) as counter')
			->addSelect('SUM(CASE WHEN h.netProfit >= 0 THEN 1 ELSE 0 END) as winners')
			->addSelect('SUM(CASE WHEN h.netProfit < 0 THEN 1 ELSE 0 END) as losers');
		
		$qb->join('h.marketType', 'market_type');
		
		$qb->where('h.trader = :trader');
		
		$qb->setParameter('trader', $user);
		
		$qb
			// ->groupBy('market')
			->addGroupBy('h.symbol')
			->addGroupBy('month');
		
		$qb
			->orderBy('month')
			->addOrderBy('profit', 'DESC')
			;
		
		return $qb->getQuery()->getResult();
	}
}
