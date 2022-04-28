<?php

namespace App\Controller\Api;

use App\Service\TradesHistoryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Ruslan Ishemgulov <ruslan.ishemgulov@gmail.com>
 */
class StatsController extends AbstractController
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
	 * @throws \Doctrine\ORM\NonUniqueResultException
	 */
	public function overall(): Response
	{
		$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
		
		if(!$user = $this->getUser()) {
			return $this->json([], Response::HTTP_FORBIDDEN);
		}
		
		return $this->json($this->tradesHistoryService->getOverallStats($user));
	}
	
}
