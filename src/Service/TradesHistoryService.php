<?php

namespace App\Service;

use App\Entity\Trades\History;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @author Ruslan Ishemgulov <ruslan.ishemgulov@gmail.com>
 */
class TradesHistoryService
{

	public const BY_DAY = 'day';

	public const BY_MONTH = 'month';

	public const BY_QUARTER = 'quarter';

	public const CACHE_INTERVAL = 'P1D';

	/** @var \Doctrine\ORM\EntityManagerInterface */
	private $em;

	/** @var \App\Repository\Trades\HistoryRepository */
	private $historyRepository;

	/** @var \Symfony\Component\Cache\Adapter\AdapterInterface */
	private $cacheAdapter;

	/**
	 * @param \Doctrine\ORM\EntityManagerInterface $em
	 */
	public function __construct(EntityManagerInterface $em, AdapterInterface $adapterInterface)
	{
		$this->em = $em;
		$this->historyRepository = $em->getRepository(History::class);
		$this->cacheAdapter = $adapterInterface;
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
		$profitLose = [];

		$cacheKey = '_' . mb_strtolower(str_replace('\\', '_', __CLASS__) . '_' . __FUNCTION__) . '_cache';
		$cached = $this->cacheAdapter->getItem($cacheKey);

		if (!$cached->isHit()) {
			foreach ($this->historyRepository->getTradesResults($user, $period) as $history) {
				$dateParts = explode(' ', $history['trade_date']);
	
				$profitLose[] = [
					'date' => $dateParts[0],
					'net_profit' => (float)$history['net_profit'],
				];
			}

			$cached->set($profitLose);
			$cached->expiresAfter(new \DateInterval(self::CACHE_INTERVAL));
			$this->cacheAdapter->save($cached);
		} else {
			$profitLose = $cached->get();
		}


		return $profitLose;
	}

	/**
	 * @var \Symfony\Component\Security\Core\User\UserInterface $user
	 * @return int|mixed|string|null
	 * @throws \Doctrine\ORM\NonUniqueResultException
	 */
	public function getOverallStats(UserInterface $user)
	{
		$cacheKey = '_' . mb_strtolower(str_replace('\\', '_', __CLASS__) . '_' . __FUNCTION__) . '_cache';

		$cached = $this->cacheAdapter->getItem($cacheKey);

		if (!$cached->isHit()) {
			$stats = $this->historyRepository->getOverallStats($user);

			$cached->set($stats);
			$cached->expiresAfter(new \DateInterval(self::CACHE_INTERVAL));
			$this->cacheAdapter->save($cached);
		} else {
			$stats = $cached->get();
		}
		
		return $stats;
	}

	/**
	 * @param \Symfony\Component\Security\Core\User\UserInterface $user
	 * @return array
	 */
	public function profitByHours(UserInterface $user): array
	{
		$stats = [];

		$cacheKey = '_' . mb_strtolower(str_replace('\\', '_', __CLASS__) . '_' . __FUNCTION__) . '_cache';

		$cached = $this->cacheAdapter->getItem($cacheKey);

		if (!$cached->isHit()) {
			foreach ($this->historyRepository->statsByHours($user) as $profitByHour) {
				$stats[$profitByHour['month']][] = [
					'time_start' => $profitByHour['time_start'],
					'time_end' => $profitByHour['time_end'],
					'time_range' => mb_strcut($profitByHour['time_start'], 0, -3) . ' - ' . mb_strcut($profitByHour['time_end'], 0, -3),
					'profit' => $profitByHour['profit'],
					'trade_counter' => $profitByHour['trade_counter'],
					'winners' => $profitByHour['winners'],
					'losers' => $profitByHour['losers']
				];
			}

			$cached->set($stats);
			$cached->expiresAfter(new \DateInterval(self::CACHE_INTERVAL));
			$this->cacheAdapter->save($cached);
		} else {
			$stats = $cached->get();
		}

		return $stats;
	}

	/**
	 * @param \Symfony\Component\Security\Core\User\UserInterface $user
	 * @return array
	 */
	public function statsByDays(UserInterface $user): array
	{
		$cacheKey = '_' . mb_strtolower(str_replace('\\', '_', __CLASS__) . '_' . __FUNCTION__) . '_cache';
		$statsByDay = [];

		$stats = $this->cacheAdapter->getItem($cacheKey);

		if (!$stats->isHit()) {
			foreach ($this->historyRepository->statsByDays($user) as $byDay) {
				$statsByDay[$byDay['month']][] = [
					'trade_day' => $byDay['trade_day'],
					'profit' => $byDay['profit'],
					'trade_counter' => $byDay['trade_counter'],
					'winners' => $byDay['winners'],
					'losers' => $byDay['losers']
				];
			}

			$stats->set($statsByDay);
			$stats->expiresAfter(new \DateInterval(self::CACHE_INTERVAL));
			$this->cacheAdapter->save($stats);
		} else {
			$statsByDay = $stats->get();
		}

		return $statsByDay;
	}

	/**
	 * @param \Symfony\Component\Security\Core\User\UserInterface $user
	 * @return array
	 */
	public function statsByMonths(UserInterface $user): array
	{
		$cacheKey = '_' . mb_strtolower(str_replace('\\', '_', __CLASS__) . '_' . __FUNCTION__) . '_cache';

		$statsByMonths = [];

		$stats = $this->cacheAdapter->getItem($cacheKey);

		if (!$stats->isHit()) {
			$statsByMonths = $this->historyRepository->statsByMonths($user);
			$stats->set($statsByMonths);
			$stats->expiresAfter(new \DateInterval(self::CACHE_INTERVAL));
			$this->cacheAdapter->save($stats);
		} else {
			$statsByMonths = $stats->get();
		}

		/*foreach ($this->historyRepository->statsByMonths() as $byMonth) {
			$stats[$byMonth['month']][] = [
				'profit' => $byMonth['profit'],
				'trade_counter' => $byMonth['trade_counter'],
				'winners' => $byMonth['winners'],
				'losers' => $byMonth['losers'],
			];
		}*/

		return $statsByMonths;
	}

	/**
	 * @param \Symfony\Component\Security\Core\User\UserInterface $user
	 * @return array
	 */
	public function statsByMarkets(UserInterface $user): array
	{
		$stats = [];

		$cacheKey = '_' . mb_strtolower(str_replace('\\', '_', __CLASS__) . '_' . __FUNCTION__) . '_cache';

		$markets = $this->cacheAdapter->getItem($cacheKey);

		if (!$markets->isHit()) {
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
			$markets->set($stats);
			$markets->expiresAfter(new \DateInterval(self::CACHE_INTERVAL));
			$this->cacheAdapter->save($markets);
		} else {
			$stats = $markets->get();
		}

		return $stats;
	}

	/**
	 * @param \Symfony\Component\Security\Core\User\UserInterface $user
	 * @return array
	 */
	public function statsByAssets(UserInterface $user): array
	{
		$assets = [];

		$cacheKey = '_' . mb_strtolower(str_replace('\\', '_', __CLASS__) . '_' . __FUNCTION__) . '_cache';

		$stats = $this->cacheAdapter->getItem($cacheKey);

		if (!$stats->isHit()) {
			foreach ($this->historyRepository->statsByAssets($user) as $byAsset) {
				$assets[$byAsset['month']][$byAsset['symbol']] = [
					'counter' => $byAsset['counter'],
					// 'symbol' => $byAsset['symbol'],
					'winners' => $byAsset['winners'],
					'losers' => $byAsset['losers'],
					'profit' => $byAsset['profit']
				];
			}

			$stats->set($assets);
			$stats->expiresAfter(new \DateInterval(self::CACHE_INTERVAL));
			$this->cacheAdapter->save($stats);
		} else {
			$assets = $stats->get();
		}

		return $assets;
	}

	/**
	 * @param \Symfony\Component\Security\Core\User\UserInterface $user
	 * @return int|mixed|string
	 */
	public function removeAllForUser(UserInterface $user)
	{
		return $this->historyRepository->removeAll($user);
	}
}
