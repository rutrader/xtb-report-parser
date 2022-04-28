<?php

namespace App\Controller\Api;

use App\Service\TradesHistoryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Ruslan Ishemgulov <ruslan.ishemgulov@gmail.com>
 */
class PerformanceController extends AbstractController
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
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function overall(): Response
	{
//		$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
		
		if(!$user = $this->getUser()) {
			return $this->json([], Response::HTTP_FORBIDDEN);
		}
		
		return $this->json($this->tradesHistoryService->getTradesResults($user, TradesHistoryService::BY_DAY));
	}
	
	/**
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function monthly(): Response
	{
//		$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
		
		if(!$user = $this->getUser()) {
			return $this->json([], Response::HTTP_FORBIDDEN);
		}
		
		return $this->json($this->tradesHistoryService->statsByMonths($user));
	}
	
	
	/**
	 * @return \Symfony\Component\HttpFoundation\JsonResponse
	 */
	public function daily(): JsonResponse
	{
//		$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
		
		if(!$user = $this->getUser()) {
			return $this->json([], Response::HTTP_FORBIDDEN);
		}
		
		return $this->json($this->tradesHistoryService->statsByDays($user));
	}
	
	/**
	 * @param \Symfony\Component\HttpFoundation\Request $request
	 * @return \Symfony\Component\HttpFoundation\JsonResponse
	 */
	public function hourly(Request $request): JsonResponse
	{
//		$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
		
		if(!$user = $this->getUser()) {
			return $this->json([], Response::HTTP_FORBIDDEN);
		}
		
		return $this->json($this->tradesHistoryService->profitByHours($user));
	}
	
}
