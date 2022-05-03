<?php

namespace App\Controller\Api;

use App\Service\TradesHistoryService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author Ruslan Ishemgulov <ruslan.ishemgulov@gmail.com>
 */
class SettingsController extends AbstractController
{
	
	/** @var \App\Service\TradesHistoryService */
	private $tradesHistoryService;
	
	/**
	 * @param \Doctrine\ORM\EntityManagerInterface $entityManager
	 * @param \App\Service\TradesHistoryService $tradesHistoryService
	 */
	public function __construct(EntityManagerInterface $entityManager, TradesHistoryService $tradesHistoryService)
	{
		$this->tradesHistoryService = $tradesHistoryService;
	}
	
	/**
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function clearHistory(): Response
	{
		if(!$user = $this->getUser()) {
			return $this->json([], Response::HTTP_FORBIDDEN);
		}
		
		return $this->json(['message' => $this->tradesHistoryService->removeAllForUser($user)]);
	}
	
}
