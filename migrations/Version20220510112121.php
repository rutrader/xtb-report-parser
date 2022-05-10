<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Cocur\Slugify\Slugify;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Hidehalo\Nanoid\Client;
use Ramsey\Uuid\Uuid;

/**
 * @author Ruslan Ishemgulov <ruslan.ishemgulov@gmail.com>
 */
final class Version20220510112121 extends AbstractMigration
{

    /**
     * @param \Doctrine\DBAL\Schema\Schema $schema
     */
    public function up(Schema $schema): void
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

        $nanoClient = new Client();
        $slugify = new Slugify();

        foreach ($markets as $market) {
            $id = Uuid::uuid4();

            $this->addSql('INSERT INTO type_market (id, name, alias, token) VALUES (?, ?, ?, ?)', [
                $id,
                $market,
                $market,
                $nanoClient->generateId(32)
            ]);
        }

        foreach ($orders as $order) {
            $id = Uuid::uuid4();

            $this->addSql('INSERT INTO type_order (id, name, alias, token) VALUES (?, ?, ?, ?)', [
                $id,
                $order,
                $slugify->slugify($order),
                $nanoClient->generateId(32)
            ]);
        }


    }

    /**
     * @param \Doctrine\DBAL\Schema\Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->addSql('DELETE FROM type_market');
        $this->addSql('DELETE FROM type_order');
    }

}
