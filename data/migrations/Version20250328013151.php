<?php

declare(strict_types=1);

namespace Skar\LaminasDoctrineORM;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250328013151 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tracking_status_updates (tracking_id INTEGER NOT NULL, status_update_id INTEGER NOT NULL, PRIMARY KEY(tracking_id, status_update_id), CONSTRAINT FK_A371438C7D05ABBE FOREIGN KEY (tracking_id) REFERENCES parcel_tracking_details (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_A371438C5D036AF0 FOREIGN KEY (status_update_id) REFERENCES parcel_status_update (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_A371438C7D05ABBE ON tracking_status_updates (tracking_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A371438C5D036AF0 ON tracking_status_updates (status_update_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__parcel_status_update AS SELECT id, description, address, created_at FROM parcel_status_update');
        $this->addSql('DROP TABLE parcel_status_update');
        $this->addSql('CREATE TABLE parcel_status_update (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, description VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, created_at DATE DEFAULT \'CURRENT_TIMESTAMP\' NOT NULL)');
        $this->addSql('INSERT INTO parcel_status_update (id, description, address, created_at) SELECT id, description, address, created_at FROM __temp__parcel_status_update');
        $this->addSql('DROP TABLE __temp__parcel_status_update');
        $this->addSql('CREATE INDEX desc_address_created_idx ON parcel_status_update (description, address, created_at)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE tracking_status_updates');
        $this->addSql('CREATE TEMPORARY TABLE __temp__parcel_status_update AS SELECT id, description, address, created_at FROM parcel_status_update');
        $this->addSql('DROP TABLE parcel_status_update');
        $this->addSql('CREATE TABLE parcel_status_update (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, description VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, created_at DATE DEFAULT \'CURRENT_TIMESTAMP\' NOT NULL, parcelTrackingDetails_id INTEGER DEFAULT NULL, CONSTRAINT FK_8FED31DAC4D09EE5 FOREIGN KEY (parcelTrackingDetails_id) REFERENCES parcel_tracking_details (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO parcel_status_update (id, description, address, created_at) SELECT id, description, address, created_at FROM __temp__parcel_status_update');
        $this->addSql('DROP TABLE __temp__parcel_status_update');
        $this->addSql('CREATE INDEX desc_address_created_idx ON parcel_status_update (description, address, created_at)');
        $this->addSql('CREATE INDEX IDX_8FED31DAC4D09EE5 ON parcel_status_update (parcelTrackingDetails_id)');
    }
}
