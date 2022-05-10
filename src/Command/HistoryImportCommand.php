<?php

namespace App\Command;

use App\Entity\Trades\History;
use App\Entity\Users\User;
use App\Service\MarketTypeService;
use App\Service\OrderTypeService;
use App\Service\TradesHistoryService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use \DateTimeImmutable;

/**
 * @author Ruslan Ishemgulov <ruslan.ishemgulov@gmail.com>
 */
class HistoryImportCommand extends Command
{

    /** @var \Doctrine\ORM\EntityManagerInterface */
    private $em;

	/** @var \App\Repository\Trades\HistoryRepository|\Doctrine\Persistence\ObjectRepository */
	private $historyRepo;

	/** @var \App\Service\OrderTypeService */
	private $typeService;

	/** @var \App\Service\MarketTypeService */
	private $marketTypeService;
	
	/** @var \App\Repository\Users\UserRepository|\Doctrine\Persistence\ObjectRepository */
	private $userRepo;

	/** @var \App\Service\TradesHistoryService */
	private $tradesHistoryService;

	/** @var string */
	protected static $defaultName = 'app:history:import';
	
	/** @var string */
	protected static $defaultDescription = 'Add a short description for your command';
	
	public function __construct(EntityManagerInterface $em,
                                TradesHistoryService $tradesHistoryService,
                                OrderTypeService $typeService, MarketTypeService $marketTypeService)
	{
		parent::__construct();
		
		$this->historyRepo = $em->getRepository(History::class);
		$this->userRepo = $em->getRepository(User::class);
		$this->tradesHistoryService = $tradesHistoryService;
		$this->typeService = $typeService;
		$this->marketTypeService = $marketTypeService;
		$this->em = $em;
	}

	protected function configure()
	{
		$this
            ->addArgument('file', InputArgument::REQUIRED, 'File path')
            ->addArgument('email', InputArgument::REQUIRED, 'User\'s email')
        ;
	}
	
	/**
	 * @param \Symfony\Component\Console\Input\InputInterface $input
	 * @param \Symfony\Component\Console\Output\OutputInterface $output
	 * @return int
	 */
	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		$io = new SymfonyStyle($input, $output);
        $filePath = $input->getArgument('file');
        $email = $input->getArgument('email');

        if(!$user = $this->userRepo->findUserByEmail($email)) {
            $io->error('User was not found');
            return Command::FAILURE;
        }

        $imported = 0;

        if (($handle = fopen($filePath, 'rb')) !== false) {

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
                        'symbol' => $data[0],
                        'position' => $data[1],
                        'orderType' => $this->typeService->getOrderTypeByName($data[2]),
                        'lots' => (float)$data[3],
                        'openedAt' => new DateTimeImmutable($data[4]),
                        'openPrice' => (float)$data[5],
                        'closedAt' => new DateTimeImmutable($data[6]),
                        'closePrice' => (float)$data[7],
                        'profit' => (float)$data[8],
                        'netProfit' => (float)$data[9],
                        'comment' => $data[10],
                        'market' => $this->marketTypeService->getMarketBySymbol($data[0]),
                        'user' => $user,
                        'importedAt' => $importedDate,
                    ], false);

                    $imported++;

                    /*if (($row % 50) === 0) {
                        $this->em->flush();
                        $imported = 0;
                    }*/
                } catch (\Exception $e) {
                    $io->error($e->getMessage());
                }
            }
        }

        if ($imported > 0) {
            $this->em->flush();
        }
		
		return Command::SUCCESS;
	}
	
}
