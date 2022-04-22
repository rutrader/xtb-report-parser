<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * @author Ruslan Ishemgulov <ruslan.ishemgulov@gmail.com>
 */
final class Version20220422164009 extends AbstractMigration
{
	
	/**
	 * @param \Doctrine\DBAL\Schema\Schema $schema
	 */
	public function up(Schema $schema): void
	{
		$this->addSql('ALTER TABLE trades_history ADD trader_id UUID NOT NULL');
		$this->addSql('ALTER TABLE trades_history ADD imported_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');

		$this->addSql('ALTER TABLE trades_history ADD CONSTRAINT FK_631A37F01273968F FOREIGN KEY (trader_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');

		$this->addSql('CREATE INDEX IDX_631A37F01273968F ON trades_history (trader_id)');

		$this->addSql('COMMENT ON COLUMN trades_history.trader_id IS \'(DC2Type:uuid)\'');
		$this->addSql('COMMENT ON COLUMN trades_history.imported_at IS \'(DC2Type:datetime_immutable)\'');
	}
	
	/**
	 * @param \Doctrine\DBAL\Schema\Schema $schema
	 */
	public function down(Schema $schema): void
	{
		$this->addSql('ALTER TABLE trades_history DROP CONSTRAINT FK_631A37F01273968F');
		$this->addSql('DROP INDEX IDX_631A37F01273968F');
		$this->addSql('ALTER TABLE trades_history DROP trader_id');
		$this->addSql('ALTER TABLE trades_history DROP imported_at');
	}
	
}
