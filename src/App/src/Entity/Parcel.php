<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Index;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table(name: 'parcel')]
class Parcel
{
    #[Id]
    #[Column(type: Types::INTEGER)]
    #[GeneratedValue(strategy: 'IDENTITY')]
    private int|null $id = null;

    #[ManyToOne(targetEntity: Parcel::class, inversedBy: 'parcels')]
    private Customer $customer;

    #[OneToOne(targetEntity: Parcel::class, inversedBy: 'parcel')]
    #[JoinColumn(name: 'parcel_id', referencedColumnName: 'id')]
    private ParcelTrackingDetails|null $parcelTrackingDetails = null;

    #[Column(name: "parcel_id", type: Types::INTEGER, length: 12, nullable: false)]
    private string|null $parcelId = null {
        get {
            return $this->parcelId;
        }
        set {
            $this->parcelId = $value;
        }
    }

    #[Column(name: 'description', type: Types::STRING, nullable: false)]
    private string $description = '' {
        get {
            return $this->description;
        }
        set {
            $this->description = $value;
        }
    }

    #[Column(name: 'dimensions', type: Types::STRING, nullable: false)]
    private string $dimensions = '' {
        get {
            return $this->dimensions;
        }
        set {
            $this->dimensions = $value;
        }
    }

    #[Column(name: 'weight', type: Types::STRING, nullable: false)]
    private string $weight = '' {
        get {
            return $this->weight;
        }
        set {
            $this->weight = $value;
        }
    }

    #[Column(name: 'delivery_service', type: Types::STRING, nullable: false)]
    private string $deliveryService = '' {
        get {
            return $this->deliveryService;
        }
        set {
            $this->deliveryService = $value;
        }
    }

    #[Column(name: 'supplier', type: Types::STRING, nullable: false)]
    private string $supplier = '' {
        get {
            return $this->supplier;
        }
        set {
            $this->supplier = $value;
        }
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

}
