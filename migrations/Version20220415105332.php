<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * @author Ruslan Ishemgulov <ruslan.ishemgulov@gmail.com>
 */
final class Version20220415105332 extends AbstractMigration
{
	
	/**
	 * @param \Doctrine\DBAL\Schema\Schema $schema
	 */
	public function up(Schema $schema): void
	{
		$this->addSql('CREATE TABLE "type_market" (id UUID NOT NULL, name VARCHAR(255) NOT NULL, alias VARCHAR(255) NOT NULL, token VARCHAR(64) NOT NULL, PRIMARY KEY(id))');
		$this->addSql('COMMENT ON COLUMN "type_market".id IS \'(DC2Type:uuid)\'');
	}
	
	/**
	 * @param \Doctrine\DBAL\Schema\Schema $schema
	 */
	public function down(Schema $schema): void
	{
		$this->addSql('DROP TABLE "type_market"');
	}
	
}
