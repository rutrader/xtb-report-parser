<?php

namespace App\Service;

use App\Entity\Trades\History;
use App\Entity\Types\Order;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @author Ruslan Ishemgulov <ruslan.ishemgulov@gmail.com>
 */
class TradesHistoryService
{


	public const BY_DAY = 'by-day';
	public const BY_MONTH = 'by-month';
	public const BY_QUARTER = 'by-quarter';
	

	/** @var \Doctrine\ORM\EntityManagerInterface */
	private $em;

	/** @var \App\Repository\Trades\HistoryRepository */
	private $historyRepository;

	/** @var \App\Repository\Types\OrderRepository */
	private $orderTypeRepository;

	/**
	 * @param \Doctrine\ORM\EntityManagerInterface $em
	 */
	public function __construct(EntityManagerInterface $em)
	{
		$this->em = $em;
		$this->historyRepository = $em->getRepository(History::class);
		$this->orderTypeRepository = $em->getRepository(Order::class);
	}

	/**
	 * @param  array        $data
	 * @param  bool|boolean $doFlush
	 * @return \App\Entity\Trades\History
	 */
	public function create(array $data, bool $doFlush = true)
	{

		$history = new History();
		$history->setSymbol(mb_strtolower($data['symbol']));
		$history->setLots($data['lots']);
		$history->setOpenedAt($data['openedAt']);
		$history->setOpenPrice($data['openPrice']);
		$history->setClosedAt($data['closedAt']);
		$history->setClosedPrice($data['closePrice']);
		$history->setProfit($data['profit']);
		$history->setNetProfit($data['netProfit']);
		$history->setOrderType($data['orderType']);
		$history->setMarketType($data['market']);

		$this->em->persist($history);

		if($doFlush) {
			$this->em->flush();
		}

		return $history;
	}


	public function getProfitAndLoss()
	{
		$pl = [];

		foreach ($this->historyRepository->findProfitAndLoss() as $history) {
			$dateParts = explode(' ', $history['trade_day']);

			$pl[] = [
				'date' => $dateParts[0],
				'net_profit' => (float)$history['net_profit']
			];
		}

		return $pl;
	}

}