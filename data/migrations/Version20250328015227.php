<?php

declare(strict_types=1);

namespace Skar\LaminasDoctrineORM;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250328015227 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__customer AS SELECT id, first_name, last_name, address FROM customer');
        $this->addSql('DROP TABLE customer');
        $this->addSql('CREATE TABLE customer (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO customer (id, first_name, last_name, address) SELECT id, first_name, last_name, address FROM __temp__customer');
        $this->addSql('DROP TABLE __temp__customer');
        $this->addSql('CREATE INDEX customer_details_idx ON customer (first_name, last_name, address)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__customer AS SELECT id, first_name, last_name, address FROM customer');
        $this->addSql('DROP TABLE customer');
        $this->addSql('CREATE TABLE customer (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO customer (id, first_name, last_name, address) SELECT id, first_name, last_name, address FROM __temp__customer');
        $this->addSql('DROP TABLE __temp__customer');
    }
}
