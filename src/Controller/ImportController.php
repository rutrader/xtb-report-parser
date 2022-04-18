<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Trades\History;
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
	 * @Route("/import", name="app_import")
	 */
	public function index(Request $request): Response
	{
		$imported = 0;
		
		if (($handle = fopen($request->files->get('report')->getPathname(), 'rb')) !== false) {
			
			$row = 0;

			while (($data = fgetcsv($handle, 1000, ';')) !== false) {
				$row++;
				if ($row === 1 || count($data) < 10) {
					continue;
				}
				
				try {
					$history = $this->tradesHistoryService->create([
						'symbol' => $data[0],
						'position' => $data[1],
						'orderType' => $this->orderTypeService->getOrderTypeByName($data[2]),
						'lots' => (float)$data[3],
						'openedAt' => new DateTimeImmutable($data[4]),
						'openPrice' => (float)$data[5],
						'closedAt' => new DateTimeImmutable($data[6]),
						'closePrice' => (float)$data[7],
						'profit' => (float)$data[8],
						'netProfit' => (float)$data[9],
						'comment' => $data[10],
						'market' => $this->marketTypeService->getMarketBySymbol($data[0]),
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
		
		if($imported > 0) {
			$this->em->flush();
		}
		
		
		return $this->json([
			'message' => 'Imported ' . $row . ' rows',
		]);
	}
	
}
