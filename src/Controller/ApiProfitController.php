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
	 * @Route("/api/profit", name="app_api_profit")
	 */
	public function index(Request $request): Response
	{

		return $this->json($this->tradesHistoryService->getProfitAndLoss());
	}

}
