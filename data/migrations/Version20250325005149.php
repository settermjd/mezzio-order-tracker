<?php

declare(strict_types=1);

namespace Skar\LaminasDoctrineORM;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250325005149 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE customer (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE parcel (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, parcel_id INTEGER NOT NULL, description VARCHAR(255) NOT NULL, dimensions VARCHAR(255) NOT NULL, weight VARCHAR(255) NOT NULL, delivery_service VARCHAR(255) NOT NULL, supplier VARCHAR(255) NOT NULL, customer_id INTEGER DEFAULT NULL, CONSTRAINT FK_C99B5D609395C3F3 FOREIGN KEY (customer_id) REFERENCES parcel (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_C99B5D60465E670C FOREIGN KEY (parcel_id) REFERENCES parcel (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_C99B5D609395C3F3 ON parcel (customer_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C99B5D60465E670C ON parcel (parcel_id)');
        $this->addSql('CREATE TABLE parcel_status_update (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, description VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, created_at DATE DEFAULT \'CURRENT_TIMESTAMP\' NOT NULL, parcelTrackingDetails_id INTEGER DEFAULT NULL, CONSTRAINT FK_8FED31DAC4D09EE5 FOREIGN KEY (parcelTrackingDetails_id) REFERENCES parcel_status_update (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_8FED31DAC4D09EE5 ON parcel_status_update (parcelTrackingDetails_id)');
        $this->addSql('CREATE INDEX desc_address_created_idx ON parcel_status_update (description, address, created_at)');
        $this->addSql('CREATE TABLE parcel_tracking_details (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, tracking_id VARCHAR(24) NOT NULL)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE customer');
        $this->addSql('DROP TABLE parcel');
        $this->addSql('DROP TABLE parcel_status_update');
        $this->addSql('DROP TABLE parcel_tracking_details');
    }
}
