<?php

namespace App\Controller;

use App\Service\TradesHistoryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Ruslan Ishemgulov <ruslan.ishemgulov@gmail.com>
 */
class ApiStatsController extends AbstractController
{
	
	/** @var \App\Service\TradesHistoryService */
	private $tradesHistoryService;
	
	/**
	 * @param \App\Service\TradesHistoryService $tradesHistoryService
	 */
	public function __construct(TradesHistoryService $tradesHistoryService)
	{
		$this->tradesHistoryService = $tradesHistoryService;
	}
	
	/**
	 * @Route("/api/profit/{period}", name="app_api_profit", methods = {"GET"}, requirements={"page"="\w\-+"})
	 */
	public function index(Request $request, $period = TradesHistoryService::BY_DAY): Response
	{
		return $this->json($this->tradesHistoryService->getProfitAndLoss($period));
	}
	
	/**
	 * @Route("/api/trades/stats", methods={"GET"})
	 * @return \Symfony\Component\HttpFoundation\JsonResponse
	 * @throws \Doctrine\ORM\NonUniqueResultException
	 */
	public function stats(): JsonResponse
	{
		return $this->json($this->tradesHistoryService->getStats());
	}
	
	/**
	 * @Route (name="api_profit_by_hours", path="/api/net-profit/by-hours")
	 * @return \Symfony\Component\HttpFoundation\JsonResponse
	 */
	public function profitByHours(): JsonResponse
	{
		return $this->json($this->tradesHistoryService->profitByHours());
	}
	
	/**
	 * @Route (name="api_profit_by_days", path="/api/time-stats/by-days")
	 * @return \Symfony\Component\HttpFoundation\JsonResponse
	 */
	public function statsByDays(): JsonResponse
	{
		return $this->json($this->tradesHistoryService->statsByDays());
	}
	
	/**
	 * @Route (name="api_stats_by_months", path="/api/time-stats/by-months")
	 * @return \Symfony\Component\HttpFoundation\JsonResponse
	 */
	public function statsByMonths(): JsonResponse
	{
		return $this->json($this->tradesHistoryService->statsByMonths());
	}
	
	/**
	 * @Route(name="api_stats_by_markets", path="/api/market-stats")
	 * @return \Symfony\Component\HttpFoundation\JsonResponse
	 */
	public function statsByMarkets($fields = ''): JsonResponse
	{
		return $this->json($this->tradesHistoryService->statsByMarkets(true, explode(',', $fields)));
	}
	
}
