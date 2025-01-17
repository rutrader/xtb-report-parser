<?php

namespace App\Repository\Types;

use App\Entity\Types\Market;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Market|null find($id, $lockMode = null, $lockVersion = null)
 * @method Market|null findOneBy(array $criteria, array $orderBy = null)
 * @method Market[]    findAll()
 * @method Market[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MarketRepository extends ServiceEntityRepository
{
	
	/**
	 * @param \Doctrine\Persistence\ManagerRegistry $registry
	 */
	public function __construct(ManagerRegistry $registry)
	{
		parent::__construct($registry, Market::class);
	}
	
	/**
	 * @param \App\Entity\Types\Market $entity
	 * @param bool $flush
	 */
	public function add(Market $entity, bool $flush = true): void
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
	public function remove(Market $entity, bool $flush = true): void
	{
		$this->_em->remove($entity);
		if ($flush) {
			$this->_em->flush();
		}
	}
	
	// /**
	//  * @return Market[] Returns an array of Market objects
	//  */
	/*
	public function findByExampleField($value)
	{
		return $this->createQueryBuilder('m')
			->andWhere('m.exampleField = :val')
			->setParameter('val', $value)
			->orderBy('m.id', 'ASC')
			->setMaxResults(10)
			->getQuery()
			->getResult()
		;
	}
	*/
	
	/*
	public function findOneBySomeField($value): ?Market
	{
		return $this->createQueryBuilder('m')
			->andWhere('m.exampleField = :val')
			->setParameter('val', $value)
			->getQuery()
			->getOneOrNullResult()
		;
	}
	*/
}
