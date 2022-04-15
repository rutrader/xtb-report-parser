<?php

namespace App\Repository\Types;

use App\Entity\Types\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
	
	/**
	 * @param \Doctrine\Persistence\ManagerRegistry $registry
	 */
	public function __construct(ManagerRegistry $registry)
	{
		parent::__construct($registry, Order::class);
	}
	
	/**
	 * @param \App\Entity\Types\Order $entity
	 * @param bool $flush
	 */
	public function add(Order $entity, bool $flush = true): void
	{
		$this->_em->persist($entity);
		if ($flush) {
			$this->_em->flush();
		}
	}
	
	/**
	 * @param \App\Entity\Types\Order $entity
	 * @param bool $flush
	 */
	public function remove(Order $entity, bool $flush = true): void
	{
		$this->_em->remove($entity);
		if ($flush) {
			$this->_em->flush();
		}
	}
	
	// /**
	//  * @return Order[] Returns an array of Order objects
	//  */
	/*
	public function findByExampleField($value)
	{
		return $this->createQueryBuilder('o')
			->andWhere('o.exampleField = :val')
			->setParameter('val', $value)
			->orderBy('o.id', 'ASC')
			->setMaxResults(10)
			->getQuery()
			->getResult()
		;
	}
	*/
	
	/*
	public function findOneBySomeField($value): ?Order
	{
		return $this->createQueryBuilder('o')
			->andWhere('o.exampleField = :val')
			->setParameter('val', $value)
			->getQuery()
			->getOneOrNullResult()
		;
	}
	*/
}
