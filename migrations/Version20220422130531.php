<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * @author Ruslan Ishemgulov <ruslan.ishemgulov@gmail.com>
 */
final class Version20220422130531 extends AbstractMigration
{
	
	/**
	 * @param \Doctrine\DBAL\Schema\Schema $schema
	 */
	public function up(Schema $schema): void
	{
		$this->addSql('CREATE TABLE "user" (id UUID NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, is_verified BOOLEAN NOT NULL, PRIMARY KEY(id))');
		$this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
		$this->addSql('COMMENT ON COLUMN "user".id IS \'(DC2Type:uuid)\'');
	}
	
	/**
	 * @param \Doctrine\DBAL\Schema\Schema $schema
	 */
	public function down(Schema $schema): void
	{
		$this->addSql('DROP TABLE "user"');
	}
	
}
