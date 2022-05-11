<?php

namespace App\Controller\Api\Performance;

use App\Service\TradesHistoryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author Ruslan Ishemgulov <ruslan.ishemgulov@gmail.com>
 */
class OverallController extends AbstractController
{
	
	/** @var \App\Service\TradesHistoryService */
	private TradesHistoryService $tradesHistoryService;
	
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
	public function __invoke(): Response
	{
		if(!$user = $this->getUser()) {
			return $this->json([], Response::HTTP_FORBIDDEN);
		}
		
		return $this->json($this->tradesHistoryService->getTradesResults($user, TradesHistoryService::BY_DAY));
	}

}
