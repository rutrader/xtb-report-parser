<?php

namespace App\Service;

use App\Entity\Types\Order;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @author Ruslan Ishemgulov <ruslan.ishemgulov@gmail.com>
 */
class OrderTypeService
{
	

	/** @var \Doctrine\ORM\EntityManagerInterface */
	private $em;

	/** @var \App\Repository\Types\OrderRepository */
	private $orderTypeRepository;


	/**
	 * @param \Doctrine\ORM\EntityManagerInterface $em
	 */
	public function __construct(EntityManagerInterface $em)
	{
		$this->em = $em;
		$this->orderTypeRepository = $em->getRepository(Order::class);
	}


	public function create($name)
	{
		$market = new Order();
		$market->setName(mb_strtolower($name));

		$this->em->persist($market);
		$this->em->flush();

		return $market;
	}

	
	/**
	 * @param  string $name
	 * @return null|\App\Entity\Types\Order
	 */
	public function getOrderTypeByName($name)
	{
		return $this->orderTypeRepository->findOneBy(['name' => mb_strtolower($name)]);
	}
}