<?php

namespace App\DataFixtures;

use App\Entity\Types\Market;
use App\Entity\Types\Order;
use App\Entity\Users\User;
use App\Service\TradesHistoryService;
use App\Tests\HelperApiTestCase;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * @author Ruslan Ishemgulov <ruslan.ishemgulov@gmail.com>
 */
class TradeFixtures extends Fixture implements DependentFixtureInterface
{

    /** @var \App\Service\TradesHistoryService */
    private $tradesHistoryService;

    /**
     * TradeFixtures constructor.
     * @param \App\Service\TradesHistoryService $tradesHistoryService
     */
    public function __construct(TradesHistoryService $tradesHistoryService)
    {
        $this->tradesHistoryService = $tradesHistoryService;
    }

    /**
     * @param \Doctrine\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        $orderTypeRepo = $manager->getRepository(Order::class);
        $marketTypeRepo = $manager->getRepository(Market::class);
        $userRepo = $manager->getRepository(User::class);

        $this->tradesHistoryService->create([
            'symbol' => 'aapl.us',
            'lots' => '10',
            'openedAt' => new \DateTimeImmutable('-1 hour'),
            'closedAt' => new \DateTimeImmutable('now'),
            'openPrice' => 100,
            'closePrice' => 150,
            'profit' => 50,
            'netProfit' => 50,
            'orderType' => $orderTypeRepo->findOneBy(['alias' => 'buy']),
            'market' => $marketTypeRepo->findOneBy(['alias' => 'stock']),
            'user' => $userRepo->findOneBy(['email' => HelperApiTestCase::$testUserEmail]),
            'importedAt' => new \DateTimeImmutable('now')
        ]);

        $this->tradesHistoryService->create([
            'symbol' => 'tsla.us',
            'lots' => '5',
            'openedAt' => new \DateTimeImmutable('-1 hour'),
            'closedAt' => new \DateTimeImmutable('now'),
            'openPrice' => 1000,
            'closePrice' => 1500,
            'profit' => 500,
            'netProfit' => 500,
            'orderType' => $orderTypeRepo->findOneBy(['alias' => 'buy']),
            'market' => $marketTypeRepo->findOneBy(['alias' => 'stock']),
            'user' => $userRepo->findOneBy(['email' => 'tester@email.loc']),
            'importedAt' => new \DateTimeImmutable('now')
        ]);

        $manager->flush();
    }

    /**
     * @return string[]
     */
    public function getDependencies(): array
    {
        return [
            UserFixtures::class
        ];
    }
}
