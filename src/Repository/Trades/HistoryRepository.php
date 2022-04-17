<?php

namespace App\Repository\Trades;

use App\Entity\Trades\History;
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
	public function findProfitAndLoss()
	{
		$qb = $this->createQueryBuilder('h');

		$qb
			->select('DATE_TRUNC(\'day\', h.openedAt) as trade_day')
			->addSelect('SUM(h.netProfit) as net_profit')
		;

		$qb->groupBy('trade_day');
		$qb->orderBy('DATE_TRUNC(\'day\', h.openedAt)', 'ASC');

		return $qb->getQuery()->getResult();
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
