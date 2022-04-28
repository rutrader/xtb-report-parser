<?php

namespace App\Service;

use App\Entity\Trades\History;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @author Ruslan Ishemgulov <ruslan.ishemgulov@gmail.com>
 */
class TradesHistoryService
{
	
	public const BY_DAY = 'day';
	
	public const BY_MONTH = 'month';
	
	public const BY_QUARTER = 'quarter';
	
	/** @var \Doctrine\ORM\EntityManagerInterface */
	private $em;
	
	/** @var \App\Repository\Trades\HistoryRepository */
	private $historyRepository;
	
	/**
	 * @param \Doctrine\ORM\EntityManagerInterface $em
	 */
	public function __construct(EntityManagerInterface $em)
	{
		$this->em = $em;
		$this->historyRepository = $em->getRepository(History::class);
	}
	
	/**
	 * @param array $data
	 * @param bool $doFlush
	 * @return \App\Entity\Trades\History
	 */
	public function create(array $data, bool $doFlush = true): History
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
		
		$history->setTrader($data['user']);
		$history->setImportedAt($data['importedAt']);
		
		$this->em->persist($history);
		
		if ($doFlush) {
			$this->em->flush();
		}
		
		return $history;
	}
	
	/**
	 * @param \Symfony\Component\Security\Core\User\UserInterface $user
	 * @param string $period
	 * @return array
	 */
	public function getTradesResults(UserInterface $user, string $period = self::BY_DAY): array
	{
		$pl = [];
		
		foreach ($this->historyRepository->getTradesResults($user, $period) as $history) {
			$dateParts = explode(' ', $history['trade_date']);
			
			$pl[] = [
				'date' => $dateParts[0],
				'net_profit' => (float)$history['net_profit'],
			];
		}
		
		return $pl;
	}
	
	/**
	 * @var \Symfony\Component\Security\Core\User\UserInterface $user
	 * @return int|mixed|string|null
	 * @throws \Doctrine\ORM\NonUniqueResultException
	 */
	public function getOverallStats(UserInterface $user)
	{
		return $this->historyRepository->getOverallStats($user);
	}
	
	/**
	 * @param \Symfony\Component\Security\Core\User\UserInterface $user
	 * @return array
	 */
	public function profitByHours(UserInterface $user): array
	{
		$stats = [];
		
		foreach ($this->historyRepository->statsByHours($user) as $profitByHour) {
			$stats[$profitByHour['month']][] = [
				'time_start' => $profitByHour['time_start'],
				'time_end' => $profitByHour['time_end'],
				'time_range' => mb_strcut($profitByHour['time_start'], 0, -3) . ' - '. mb_strcut($profitByHour['time_end'], 0, -3),
				'profit' => $profitByHour['profit'],
				'trade_counter' => $profitByHour['trade_counter'],
				'winners' => $profitByHour['winners'],
				'losers' => $profitByHour['losers']
			];
		}
		return $stats;
	}
	
	/**
	 * @param \Symfony\Component\Security\Core\User\UserInterface $user
	 * @return array
	 */
	public function statsByDays(UserInterface $user): array
	{
		$stats = [];
		
		foreach ($this->historyRepository->statsByDays($user) as $byDay) {
			$stats[$byDay['month']][] = [
				'trade_day' => $byDay['trade_day'],
				'profit' => $byDay['profit'],
				'trade_counter' => $byDay['trade_counter'],
				'winners' => $byDay['winners'],
				'losers' => $byDay['losers']
			];
		}
		return $stats;
	}
	
	/**
	 * @param \Symfony\Component\Security\Core\User\UserInterface $user
	 * @return array
	 */
	public function statsByMonths(UserInterface $user): array
	{
		$stats = [];
		
		/*foreach ($this->historyRepository->statsByMonths() as $byMonth) {
			$stats[$byMonth['month']][] = [
				'profit' => $byMonth['profit'],
				'trade_counter' => $byMonth['trade_counter'],
				'winners' => $byMonth['winners'],
				'losers' => $byMonth['losers'],
			];
		}*/
		
		return $this->historyRepository->statsByMonths($user);
	}
	
	/**
	 * @param \Symfony\Component\Security\Core\User\UserInterface $user
	 * @return array
	 */
	public function statsByMarkets(UserInterface $user): array
	{
		$stats = [];
		
		foreach ($this->historyRepository->statsByMarkets($user) as $byMarket) {
			/*$stats[$byMarket['month']][] = [
				'profit' => $byMarket['profit'],
				'market' => $byMarket['market'],
				'market_counter' => $byMarket['market_counter'],
				'winners' => $byMarket['winners'],
				'losers' => $byMarket['losers'],
			];*/
			$stats[$byMarket['market']][$byMarket['month']] = [
				'market_counter' => $byMarket['market_counter'],
				'winners' => $byMarket['winners'],
				'losers' => $byMarket['losers'],
			];
		}
		
		return $stats;
	}
}
