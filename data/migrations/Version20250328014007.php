<?php

declare(strict_types=1);

namespace Skar\LaminasDoctrineORM;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250328014007 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__parcel AS SELECT id, parcel_id, description, dimensions, weight, delivery_service, supplier, parcelTrackingDetails_id FROM parcel');
        $this->addSql('DROP TABLE parcel');
        $this->addSql('CREATE TABLE parcel (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, parcel_id INTEGER NOT NULL, description VARCHAR(255) NOT NULL, dimensions VARCHAR(255) NOT NULL, weight VARCHAR(255) NOT NULL, delivery_service VARCHAR(255) NOT NULL, supplier VARCHAR(255) NOT NULL, parcel_tracking_id INTEGER DEFAULT NULL, CONSTRAINT FK_C99B5D6095EF2366 FOREIGN KEY (parcel_tracking_id) REFERENCES parcel_tracking_details (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO parcel (id, parcel_id, description, dimensions, weight, delivery_service, supplier, parcel_tracking_id) SELECT id, parcel_id, description, dimensions, weight, delivery_service, supplier, parcelTrackingDetails_id FROM __temp__parcel');
        $this->addSql('DROP TABLE __temp__parcel');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C99B5D6095EF2366 ON parcel (parcel_tracking_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__parcel AS SELECT id, parcel_id, description, dimensions, weight, delivery_service, supplier, parcel_tracking_id FROM parcel');
        $this->addSql('DROP TABLE parcel');
        $this->addSql('CREATE TABLE parcel (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, parcel_id INTEGER NOT NULL, description VARCHAR(255) NOT NULL, dimensions VARCHAR(255) NOT NULL, weight VARCHAR(255) NOT NULL, delivery_service VARCHAR(255) NOT NULL, supplier VARCHAR(255) NOT NULL, parcelTrackingDetails_id INTEGER DEFAULT NULL, CONSTRAINT FK_C99B5D60C4D09EE5 FOREIGN KEY (parcelTrackingDetails_id) REFERENCES parcel_tracking_details (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO parcel (id, parcel_id, description, dimensions, weight, delivery_service, supplier, parcelTrackingDetails_id) SELECT id, parcel_id, description, dimensions, weight, delivery_service, supplier, parcel_tracking_id FROM __temp__parcel');
        $this->addSql('DROP TABLE __temp__parcel');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C99B5D60C4D09EE5 ON parcel (parcelTrackingDetails_id)');
    }
}
