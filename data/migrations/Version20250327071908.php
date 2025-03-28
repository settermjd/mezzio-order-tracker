<?php

declare(strict_types=1);

namespace Skar\LaminasDoctrineORM;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250327071908 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__parcel_status_update AS SELECT id, description, address, created_at, tracking_id FROM parcel_status_update');
        $this->addSql('DROP TABLE parcel_status_update');
        $this->addSql('CREATE TABLE parcel_status_update (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, description VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, created_at DATE DEFAULT \'CURRENT_TIMESTAMP\' NOT NULL, parcelTrackingDetails_id INTEGER DEFAULT NULL, CONSTRAINT FK_8FED31DAC4D09EE5 FOREIGN KEY (parcelTrackingDetails_id) REFERENCES parcel_tracking_details (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO parcel_status_update (id, description, address, created_at, parcelTrackingDetails_id) SELECT id, description, address, created_at, tracking_id FROM __temp__parcel_status_update');
        $this->addSql('DROP TABLE __temp__parcel_status_update');
        $this->addSql('CREATE INDEX desc_address_created_idx ON parcel_status_update (description, address, created_at)');
        $this->addSql('CREATE INDEX IDX_8FED31DAC4D09EE5 ON parcel_status_update (parcelTrackingDetails_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__parcel_status_update AS SELECT id, description, address, created_at, parcelTrackingDetails_id FROM parcel_status_update');
        $this->addSql('DROP TABLE parcel_status_update');
        $this->addSql('CREATE TABLE parcel_status_update (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, description VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, created_at DATE DEFAULT \'CURRENT_TIMESTAMP\' NOT NULL, tracking_id INTEGER DEFAULT NULL, CONSTRAINT FK_8FED31DA7D05ABBE FOREIGN KEY (tracking_id) REFERENCES parcel_status_update (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO parcel_status_update (id, description, address, created_at, tracking_id) SELECT id, description, address, created_at, parcelTrackingDetails_id FROM __temp__parcel_status_update');
        $this->addSql('DROP TABLE __temp__parcel_status_update');
        $this->addSql('CREATE INDEX desc_address_created_idx ON parcel_status_update (description, address, created_at)');
        $this->addSql('CREATE INDEX IDX_8FED31DA7D05ABBE ON parcel_status_update (tracking_id)');
    }
}
