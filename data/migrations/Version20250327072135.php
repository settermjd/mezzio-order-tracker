<?php

declare(strict_types=1);

namespace Skar\LaminasDoctrineORM;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250327072135 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__parcel AS SELECT id, parcel_id, description, dimensions, weight, delivery_service, supplier, customer_id FROM parcel');
        $this->addSql('DROP TABLE parcel');
        $this->addSql('CREATE TABLE parcel (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, parcel_id INTEGER NOT NULL, description VARCHAR(255) NOT NULL, dimensions VARCHAR(255) NOT NULL, weight VARCHAR(255) NOT NULL, delivery_service VARCHAR(255) NOT NULL, supplier VARCHAR(255) NOT NULL, customer_id INTEGER DEFAULT NULL, parcelTrackingDetails_id INTEGER DEFAULT NULL, CONSTRAINT FK_C99B5D609395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_C99B5D60C4D09EE5 FOREIGN KEY (parcelTrackingDetails_id) REFERENCES parcel_tracking_details (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO parcel (id, parcel_id, description, dimensions, weight, delivery_service, supplier, customer_id) SELECT id, parcel_id, description, dimensions, weight, delivery_service, supplier, customer_id FROM __temp__parcel');
        $this->addSql('DROP TABLE __temp__parcel');
        $this->addSql('CREATE INDEX IDX_C99B5D609395C3F3 ON parcel (customer_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C99B5D60C4D09EE5 ON parcel (parcelTrackingDetails_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__parcel_tracking_details AS SELECT id, tracking_id FROM parcel_tracking_details');
        $this->addSql('DROP TABLE parcel_tracking_details');
        $this->addSql('CREATE TABLE parcel_tracking_details (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, tracking_number VARCHAR(24) NOT NULL)');
        $this->addSql('INSERT INTO parcel_tracking_details (id, tracking_number) SELECT id, tracking_id FROM __temp__parcel_tracking_details');
        $this->addSql('DROP TABLE __temp__parcel_tracking_details');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__parcel AS SELECT id, parcel_id, description, dimensions, weight, delivery_service, supplier, customer_id FROM parcel');
        $this->addSql('DROP TABLE parcel');
        $this->addSql('CREATE TABLE parcel (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, parcel_id INTEGER NOT NULL, description VARCHAR(255) NOT NULL, dimensions VARCHAR(255) NOT NULL, weight VARCHAR(255) NOT NULL, delivery_service VARCHAR(255) NOT NULL, supplier VARCHAR(255) NOT NULL, customer_id INTEGER DEFAULT NULL, parcel_tracking_id VARCHAR(24) DEFAULT NULL, CONSTRAINT FK_C99B5D609395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_C99B5D6095EF2366 FOREIGN KEY (parcel_tracking_id) REFERENCES parcel_tracking_details (tracking_id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO parcel (id, parcel_id, description, dimensions, weight, delivery_service, supplier, customer_id) SELECT id, parcel_id, description, dimensions, weight, delivery_service, supplier, customer_id FROM __temp__parcel');
        $this->addSql('DROP TABLE __temp__parcel');
        $this->addSql('CREATE INDEX IDX_C99B5D609395C3F3 ON parcel (customer_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C99B5D6095EF2366 ON parcel (parcel_tracking_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__parcel_tracking_details AS SELECT id, tracking_number FROM parcel_tracking_details');
        $this->addSql('DROP TABLE parcel_tracking_details');
        $this->addSql('CREATE TABLE parcel_tracking_details (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, tracking_id VARCHAR(24) NOT NULL)');
        $this->addSql('INSERT INTO parcel_tracking_details (id, tracking_id) SELECT id, tracking_number FROM __temp__parcel_tracking_details');
        $this->addSql('DROP TABLE __temp__parcel_tracking_details');
    }
}
