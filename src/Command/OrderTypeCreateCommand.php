<?php

namespace App\Command;

use App\Service\OrderTypeService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * @author Ruslan Ishemgulov <rusln.ishemgulov@gmail.com>
 */
class OrderTypeCreateCommand extends Command
{

	/** @var \App\Service\OrderTypeService */
	private $orderTypeService;

	/** @var string */
	protected static $defaultName = 'app:order-type:create';
	
	public function __construct(OrderTypeService $orderTypeService)
	{
		parent::__construct();
		
		$this->orderTypeService = $orderTypeService;
	}

	protected function configure(): void
	{
		$this
			->addArgument('name', InputArgument::REQUIRED)
		;
	}

	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		$io = new SymfonyStyle($input, $output);
		$name = $input->getArgument('name');


		$this->orderTypeService->create($name);


		return Command::SUCCESS;
	}
}
