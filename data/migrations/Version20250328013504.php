<?php

declare(strict_types=1);

namespace Skar\LaminasDoctrineORM;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250328013504 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE customer_parcels (customer_id INTEGER NOT NULL, parcel_id INTEGER NOT NULL, PRIMARY KEY(customer_id, parcel_id), CONSTRAINT FK_4AA891B09395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_4AA891B0465E670C FOREIGN KEY (parcel_id) REFERENCES parcel (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_4AA891B09395C3F3 ON customer_parcels (customer_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4AA891B0465E670C ON customer_parcels (parcel_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__parcel AS SELECT id, parcel_id, description, dimensions, weight, delivery_service, supplier, parcelTrackingDetails_id FROM parcel');
        $this->addSql('DROP TABLE parcel');
        $this->addSql('CREATE TABLE parcel (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, parcel_id INTEGER NOT NULL, description VARCHAR(255) NOT NULL, dimensions VARCHAR(255) NOT NULL, weight VARCHAR(255) NOT NULL, delivery_service VARCHAR(255) NOT NULL, supplier VARCHAR(255) NOT NULL, parcelTrackingDetails_id INTEGER DEFAULT NULL, CONSTRAINT FK_C99B5D60C4D09EE5 FOREIGN KEY (parcelTrackingDetails_id) REFERENCES parcel_tracking_details (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO parcel (id, parcel_id, description, dimensions, weight, delivery_service, supplier, parcelTrackingDetails_id) SELECT id, parcel_id, description, dimensions, weight, delivery_service, supplier, parcelTrackingDetails_id FROM __temp__parcel');
        $this->addSql('DROP TABLE __temp__parcel');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C99B5D60C4D09EE5 ON parcel (parcelTrackingDetails_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE customer_parcels');
        $this->addSql('CREATE TEMPORARY TABLE __temp__parcel AS SELECT id, parcel_id, description, dimensions, weight, delivery_service, supplier, parcelTrackingDetails_id FROM parcel');
        $this->addSql('DROP TABLE parcel');
        $this->addSql('CREATE TABLE parcel (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, parcel_id INTEGER NOT NULL, description VARCHAR(255) NOT NULL, dimensions VARCHAR(255) NOT NULL, weight VARCHAR(255) NOT NULL, delivery_service VARCHAR(255) NOT NULL, supplier VARCHAR(255) NOT NULL, parcelTrackingDetails_id INTEGER DEFAULT NULL, customer_id INTEGER DEFAULT NULL, CONSTRAINT FK_C99B5D60C4D09EE5 FOREIGN KEY (parcelTrackingDetails_id) REFERENCES parcel_tracking_details (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_C99B5D609395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO parcel (id, parcel_id, description, dimensions, weight, delivery_service, supplier, parcelTrackingDetails_id) SELECT id, parcel_id, description, dimensions, weight, delivery_service, supplier, parcelTrackingDetails_id FROM __temp__parcel');
        $this->addSql('DROP TABLE __temp__parcel');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C99B5D60C4D09EE5 ON parcel (parcelTrackingDetails_id)');
        $this->addSql('CREATE INDEX IDX_C99B5D609395C3F3 ON parcel (customer_id)');
    }
}
