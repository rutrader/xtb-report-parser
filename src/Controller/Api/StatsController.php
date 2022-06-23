<?php

namespace App\Controller\Api;

use App\Service\SettingsService;
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

	/** @var \App\Service\SettingsService */
	private $settingsService;

	
	/**
	 * @param \App\Service\TradesHistoryService $tradesHistoryService
	 */
	public function __construct(TradesHistoryService $tradesHistoryService, SettingsService $settingsService)
	{
		$this->tradesHistoryService = $tradesHistoryService;
		$this->settingsService = $settingsService;
	}

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
	public function __invoke(): JsonResponse
    {
		$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

		$user = $this->getUser();

		$stats = $this->tradesHistoryService->getOverallStats($user);
		$account = [
			'currency' => $this->settingsService->getUserCurrency($user)
		];

        return $this->json(array_merge($stats, $account));
    }

}
