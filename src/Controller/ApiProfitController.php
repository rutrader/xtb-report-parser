<?php

namespace App\Controller;

use App\Service\TradesHistoryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Ruslan Ishemgulov <ruslan.ishemgulov@gmail.com>
 */
class ApiProfitController extends AbstractController
{


	private $tradesHistoryService;

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
	 * @Route("/api/profit-loss-count")
	 * @return [type] [description]
	 */
	public function countPositiveNegativeOrders()
	{
		return $this->json($this->tradesHistoryService->countProfitAndLoss());
	}
}
