<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * @author Ruslan Ishemgulov <ruslan.ishemgulov@gmail.com>
 */
final class Version20220415120021 extends AbstractMigration
{
	
	/**
	 * @param \Doctrine\DBAL\Schema\Schema $schema
	 */
	public function up(Schema $schema): void
	{
		$this->addSql('CREATE TABLE trades_history (id UUID NOT NULL, order_type_id UUID NOT NULL, market_type_id UUID NOT NULL, symbol VARCHAR(255) NOT NULL, lots DOUBLE PRECISION NOT NULL, opened_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, open_price DOUBLE PRECISION NOT NULL, closed_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, closed_price DOUBLE PRECISION NOT NULL, profit DOUBLE PRECISION NOT NULL, net_profit DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');

		$this->addSql('CREATE INDEX IDX_631A37F0333625D8 ON trades_history (order_type_id)');
		$this->addSql('CREATE INDEX IDX_631A37F0CCDA6413 ON trades_history (market_type_id)');

		$this->addSql('ALTER TABLE trades_history ADD CONSTRAINT FK_631A37F0333625D8 FOREIGN KEY (order_type_id) REFERENCES "type_order" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
		$this->addSql('ALTER TABLE trades_history ADD CONSTRAINT FK_631A37F0CCDA6413 FOREIGN KEY (market_type_id) REFERENCES "type_market" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');

		$this->addSql('COMMENT ON COLUMN trades_history.id IS \'(DC2Type:uuid)\'');
		$this->addSql('COMMENT ON COLUMN trades_history.order_type_id IS \'(DC2Type:uuid)\'');
		$this->addSql('COMMENT ON COLUMN trades_history.market_type_id IS \'(DC2Type:uuid)\'');
		$this->addSql('COMMENT ON COLUMN trades_history.opened_at IS \'(DC2Type:datetime_immutable)\'');
		$this->addSql('COMMENT ON COLUMN trades_history.closed_at IS \'(DC2Type:datetime_immutable)\'');
	}
	
	/**
	 * @param \Doctrine\DBAL\Schema\Schema $schema
	 */
	public function down(Schema $schema): void
	{
		$this->addSql('DROP TABLE trades_history');
	}
	
}
