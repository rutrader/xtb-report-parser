<?php

namespace App\Service;

use App\Entity\Types\Market;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @author Ruslan Ishemgulov <ruslan.ishemgulov@gmail.com>
 */
class MarketTypeService
{
	

	/** @var \Doctrine\ORM\EntityManagerInterface */
	private $em;

	/** @var \App\Repository\Types\MarketRepository */
	private $marketTypeRepository;

	private $coins = [
		'cardano',
		'ripple',
		'polkadot',
		'bitcoin',
	];

	/**
	 * @param \Doctrine\ORM\EntityManagerInterface $em
	 */
	public function __construct(EntityManagerInterface $em)
	{
		$this->em = $em;
		$this->marketTypeRepository = $em->getRepository(Market::class);
	}


	public function create($name)
	{
		$market = new Market();
		$market->setName($name);

		$this->em->persist($market);
		$this->em->flush();

		return $market;
	}

	
	/**
	 * @param  string $symbol
	 * @return null|\App\Entity\Types\Market
	 */
	public function getMarketBySymbol($symbol)
	{

		$type = null;

		if(preg_match('/(?:(EUR|USD|GBP|JPY|BTC)(?!\1)){2}/', $symbol)) {
			$type = 'forex';
		} elseif(preg_match('/\b\.(US)/', $symbol)) {
			$type = 'stock';
		} elseif (in_array(strtolower($symbol), $this->coins)) {
			$type = 'crypto';
		} else {
			$type = 'unknown';
		}

		$marketType = $this->marketTypeRepository->findOneBy(['name' => $type]);

		return $marketType;
	}
}