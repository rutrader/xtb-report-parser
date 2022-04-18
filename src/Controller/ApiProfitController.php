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
class ApiProfitController extends AbstractController
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
	 */
	public function stats(): JsonResponse
	{
		return $this->json($this->tradesHistoryService->getStats());
	}
	
}
