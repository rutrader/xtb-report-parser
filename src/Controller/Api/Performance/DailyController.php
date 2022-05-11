<?php

namespace App\Controller\Api\Performance;

use App\Service\TradesHistoryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Ruslan Ishemgulov <ruslan.ishemgulov@gmail.com>
 */
class DailyController extends AbstractController
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
     */
    public function __invoke(): JsonResponse
    {
        if(!$user = $this->getUser()) {
            return $this->json([], Response::HTTP_FORBIDDEN);
        }

        return $this->json($this->tradesHistoryService->statsByDays($user));
    }


}