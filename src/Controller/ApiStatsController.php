<?php

namespace App\Controller;

use App\Service\TradesHistoryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
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
	 * @param \Symfony\Component\HttpFoundation\Request $request
	 * @param string $period
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function index(Request $request, string $period = TradesHistoryService::BY_DAY): Response
	{
		return $this->json($this->tradesHistoryService->getProfitAndLoss($period));
	}
	
	/**
	 * @return \Symfony\Component\HttpFoundation\JsonResponse
	 * @throws \Doctrine\ORM\NonUniqueResultException
	 */
	public function stats(): JsonResponse
	{
		return $this->json($this->tradesHistoryService->getStats());
	}
	
	/**
	 * @return \Symfony\Component\HttpFoundation\JsonResponse
	 */
	public function profitByHours(): JsonResponse
	{
		return $this->json($this->tradesHistoryService->profitByHours());
	}
	
	/**
	 * @return \Symfony\Component\HttpFoundation\JsonResponse
	 */
	public function statsByDays(): JsonResponse
	{
		return $this->json($this->tradesHistoryService->statsByDays());
	}
	
	/**
	 * @return \Symfony\Component\HttpFoundation\JsonResponse
	 */
	public function statsByMonths(): JsonResponse
	{
		return $this->json($this->tradesHistoryService->statsByMonths());
	}
	
	/**
	 * @param string $fields
	 * @return \Symfony\Component\HttpFoundation\JsonResponse
	 */
	public function statsByMarkets(string $fields = ''): JsonResponse
	{
		return $this->json($this->tradesHistoryService->statsByMarkets(true, explode(',', $fields)));
	}
	
}
