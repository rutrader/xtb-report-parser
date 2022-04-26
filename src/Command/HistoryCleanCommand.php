<?php

namespace App\Command;

use App\Entity\Trades\History;
use App\Entity\Users\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * @author Ruslan Ishemgulov <ruslan.ishemgulov@gmail.com>
 */
class HistoryCleanCommand extends Command
{
	
	/** @var \App\Repository\Trades\HistoryRepository|\Doctrine\Persistence\ObjectRepository */
	private $historyRepo;
	
	/** @var \App\Repository\Users\UserRepository|\Doctrine\Persistence\ObjectRepository */
	private $userRepo;
	
	/** @var string */
	protected static $defaultName = 'app:history:clean';
	
	/** @var string */
	protected static $defaultDescription = 'Add a short description for your command';
	
	public function __construct(EntityManagerInterface $em)
	{
		parent::__construct();
		
//		$this->em = $em;
		$this->historyRepo = $em->getRepository(History::class);
		$this->userRepo = $em->getRepository(User::class);
	}

	protected function configure()
	{
		$this->addArgument('trader', InputArgument::REQUIRED, 'Trader\'s email');
	}
	
	/**
	 * @param \Symfony\Component\Console\Input\InputInterface $input
	 * @param \Symfony\Component\Console\Output\OutputInterface $output
	 * @return int
	 */
	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		$io = new SymfonyStyle($input, $output);
		
		$trader = $input->getArgument('trader');
		
		if(!$user = $this->userRepo->findUserByEmail($trader)) {
			$io->error('User was not found');
			return Command::FAILURE;
		}
		
		$helper = $this->getHelper('question');
		
		$question = new ConfirmationQuestion('Are you sure?', false);
		
		if (!$helper->ask($input, $output, $question)) {
			return Command::SUCCESS;
		}
		
		$this->historyRepo->removeAll($user);
		
		return Command::SUCCESS;
	}
	
}
