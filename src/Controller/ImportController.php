<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Service\TradesHistoryService;
use App\Service\MarketTypeService;
use App\Service\OrderTypeService;
use \DateTimeImmutable;

/**
 * @author Ruslan Ishemgulov <ruslan.ishemgulov@gmail.com>
 */
class ImportController extends AbstractController
{
	
	/** @var \Doctrine\ORM\EntityManagerInterface */
	private $em;
	
	/** @var \App\Service\MarketTypeService */
	private $marketTypeService;
	
	/** @var \App\Service\OrderTypeService */
	private $orderTypeService;
	
	/** @var \App\Service\TradesHistoryService */
	private $tradesHistoryService;
	
	/**
	 * @param \Doctrine\ORM\EntityManagerInterface $em
	 */
	public function __construct(EntityManagerInterface $em, TradesHistoryService $tradesHistoryService, MarketTypeService $marketTypeService, OrderTypeService $orderTypeService)
	{
		$this->em = $em;
		$this->tradesHistoryService = $tradesHistoryService;
		$this->marketTypeService = $marketTypeService;
		$this->orderTypeService = $orderTypeService;
	}
	
	/**
	 * @param \Symfony\Component\HttpFoundation\Request $request
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function index(Request $request): Response
	{
		return $this->json([
			'message' => 'Imported ' . 1 . ' rows',
		]);

		$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
		
		if(!$user = $this->getUser()) {
			return $this->json([], Response::HTTP_FORBIDDEN);
		}
		
		$imported = 0;
		
		$fields = json_decode($request->request->get('fields'), true);

		if (($handle = fopen($request->files->get('report')->getPathname(), 'rb')) !== false) {
			
			$this->tradesHistoryService->removeAllForUser($user);
			
			$row = 0;

			$importedDate = new DateTimeImmutable;
			
			while (($data = fgetcsv($handle, 1000, ';')) !== false) {
				$row++;
				if ($row === 1 || count($data) < 10) {
					continue;
				}
		
				try {
					$history = $this->tradesHistoryService->create([
						'symbol' => $data[$fields['symbol']],
						// 'symbol' => $data[$fields['symbol']]
						'position' => $data[$fields['position']],
						'orderType' => $this->orderTypeService->getOrderTypeByName($data[$fields['orderType']]),
						'lots' => (float)$data[$fields['lots']],
						'openedAt' => new DateTimeImmutable($data[$fields['openedAt']]),
						'openPrice' => (float)$data[$fields['openPrice']],
						'closedAt' => new DateTimeImmutable($data[$fields['closedAt']]),
						'closePrice' => (float)$data[$fields['closePrice']],
						'profit' => (float)$data[$fields['profit']],
						'netProfit' => (float)$data[$fields['netProfit']],
						// 'comment' => $data[10],
						'market' => $this->marketTypeService->getMarketBySymbol($data[$fields['symbol']]),
						'user' => $user,
						'importedAt' => $importedDate,
					], false);
					
					$imported++;
					
					/*if (($row % 50) === 0) {
						$this->em->flush();
						$imported = 0;
					}*/
				} catch (\Exception $e) {
					return $this->json(['message' => $e->getMessage()], 500);
				}
			}
		}
		
		if ($imported > 0) {
			$this->em->flush();
		}
		
		
		return $this->json([
			'message' => 'Imported ' . $row . ' rows',
		]);
	}
	
}
