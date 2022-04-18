<?php

namespace App\Repository\Trades;

use App\Entity\Trades\History;
use App\Service\TradesHistoryService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method History|null find($id, $lockMode = null, $lockVersion = null)
 * @method History|null findOneBy(array $criteria, array $orderBy = null)
 * @method History[]    findAll()
 * @method History[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HistoryRepository extends ServiceEntityRepository
{
	
	/**
	 * @param ManagerRegistry $registry [description]
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
	 * @return array
	 */
	public function findProfitAndLoss($period)
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
		
		$qb->setParameter('period', $period);
		
		$qb
			->groupBy('trade_date');
		
		$qb->orderBy('trade_date', 'ASC');
		
		return $qb->getQuery()->getResult();
	}
	
	/**
	 * @return int|mixed|string|null
	 * @throws \Doctrine\ORM\NonUniqueResultException
	 */
	public function getStats()
	{
		$qb = $this->createQueryBuilder('h');
		
		$qb
			->select('SUM(CASE WHEN h.netProfit >= 0 THEN 1 ELSE 0 END) as profit_orders')
			->addSelect('SUM(CASE WHEN h.netProfit < 0 THEN 1 ELSE 0 END) as loss_orders')
			->addSelect('SUM(CASE WHEN LOWER(order_type.alias) like :buy THEN 1 ELSE 0 END) as buy_orders')
			->addSelect('SUM(CASE WHEN LOWER(order_type.alias) like :sell THEN 1 ELSE 0 END) as sell_orders')
			->addSelect('SUM(h.netProfit) as pl')
			->addSelect('COUNT(h.id) as total_orders')
		;
		
		$qb->join('h.orderType', 'order_type');
		
		$qb
			->setParameter('buy', 'buy%')
			->setParameter('sell', 'sell%')
		;
		
		return $qb->getQuery()->getOneOrNullResult();
	}
	
	// /**
	//  * @return History[] Returns an array of History objects
	//  */
	/*
	public function findByExampleField($value)
	{
		return $this->createQueryBuilder('h')
			->andWhere('h.exampleField = :val')
			->setParameter('val', $value)
			->orderBy('h.id', 'ASC')
			->setMaxResults(10)
			->getQuery()
			->getResult()
		;
	}
	*/
	
	/*
	public function findOneBySomeField($value): ?History
	{
		return $this->createQueryBuilder('h')
			->andWhere('h.exampleField = :val')
			->setParameter('val', $value)
			->getQuery()
			->getOneOrNullResult()
		;
	}
	*/
}
