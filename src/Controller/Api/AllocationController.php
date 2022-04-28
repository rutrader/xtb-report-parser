<?php

namespace App\Controller\Api;

use App\Service\TradesHistoryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Ruslan Ishemgulov <ruslan.ishemgulov@gmail.com>
 */
class AllocationController extends AbstractController
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
	 * @return \Symfony\Component\HttpFoundation\JsonResponse
	 */
	public function markets(): JsonResponse
	{
		if(!$user = $this->getUser()) {
			return $this->json([], Response::HTTP_FORBIDDEN);
		}
		
		return $this->json($this->tradesHistoryService->statsByMarkets($user));
	}
	
}
