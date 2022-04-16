<?php

namespace App\Command;

use App\Service\MarketTypeService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * @author Ruslan Ishemgulov <rusln.ishemgulov@gmail.com>
 */
class MarketTypeCreateCommand extends Command
{

	/** @var \App\Service\MarketTypeService */
	private $marketTypeService;

	/** @var string */
	protected static $defaultName = 'app:market-type:create';
	
	public function __construct(MarketTypeService $marketTypeService)
	{
		parent::__construct();
		
		$this->marketTypeService = $marketTypeService;
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


		$this->marketTypeService->create($name);


		return Command::SUCCESS;
	}
}
