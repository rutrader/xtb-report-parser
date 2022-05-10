<?php

namespace App\DataFixtures;

use App\Entity\Types\Market;
use App\Entity\Types\Order;
use Cocur\Slugify\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TypesFixtures extends Fixture
{

    /**
     * @param \Doctrine\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        $markets = [
            'forex',
            'crypto',
            'etf',
            'stock',
        ];

        $orders = [
            'sell',
            'buy',
            'sell limit',
            'buy limit',
            'sell stop',
            'buy stop'
        ];

        $slugify = new Slugify();

        foreach ($markets as $marketName) {
            $market = new Market();
            $market->setAlias($slugify->slugify($marketName));
            $market->setName($marketName);

            $manager->persist($market);
        }

        foreach ($orders as $orderName) {
            $order = new Order();
            $order->setAlias($slugify->slugify($orderName));
            $order->setName($orderName);

            $manager->persist($order);
        }

        $manager->flush();
    }
}
