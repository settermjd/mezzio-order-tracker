<?php

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Index;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table(name: 'parcel_status_update')]
#[Index(
    name: "desc_address_created_idx",
    columns: [
        "description",
        "address",
        "created_at"
    ]
)]
class ParcelStatusUpdate
{
    #[Id]
    #[Column(type: Types::INTEGER)]
    #[GeneratedValue(strategy: 'IDENTITY')]
    private int|null $id = null;

    #[Column(name: 'description', type: Types::STRING, nullable: false)]
    private string $description = '' {
        get {
            return $this->description;
        }
        set {
            $this->description = $value;
        }
    }

    #[Column(name: 'address', type: Types::STRING, nullable: false)]
    private string $address = '' {
        get {
            return $this->address;
        }
        set {
            $this->address = $value;
        }
    }

    #[Column(name: 'created_at', type: Types::DATE_IMMUTABLE, nullable: false, options: ["default" => "CURRENT_TIMESTAMP"])]
    private DateTimeImmutable $createdAt {
        get {
            return $this->createdAt;
        }
        set {
            $this->createdAt = $value;
        }
    }

    #[ManyToOne(targetEntity: ParcelStatusUpdate::class, inversedBy: 'parcelStatusUpdates')]
    private ParcelTrackingDetails $parcelTrackingDetails;


    public function getParcelTrackingDetails(): ?ParcelTrackingDetails
    {
        return $this->parcelTrackingDetails;
    }

    public function setParcelTrackingDetails(?ParcelTrackingDetails $parcelTrackingDetails): self
    {
        $this->parcelTrackingDetails = $parcelTrackingDetails;

        return $this;
    }

}
