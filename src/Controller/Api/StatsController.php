<?php

namespace App\Controller\Api;

use App\Service\TradesHistoryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author Ruslan Ishemgulov <ruslan.ishemgulov@gmail.com>
 */
class StatsController extends AbstractController
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
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
	public function __invoke(): JsonResponse
    {
        return $this->json($this->tradesHistoryService->getOverallStats($this->getUser()));
    }

}
