<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ParcelRepository;
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

#[Entity(repositoryClass: ParcelRepository::class)]
#[Table(name: 'parcel')]
#[Index(
    name: "parcel_details_idx",
    columns: [
        "parcel_id",
        "description",
        "dimensions",
        "weight",
        "delivery_service",
        "supplier",
    ]
)]
class Parcel
{
    #[Id]
    #[Column(type: Types::INTEGER)]
    #[GeneratedValue(strategy: 'AUTO')]
    private int|null $id = null;

    #[OneToOne(targetEntity: ParcelTrackingDetails::class, inversedBy: 'parcel')]
    #[JoinColumn(name: 'parcel_tracking_id', referencedColumnName: 'id', unique: true, onDelete: 'CASCADE')]
    private ParcelTrackingDetails|null $parcelTrackingDetails = null;

    #[Column(name: "parcel_id", type: Types::STRING, length: 12, nullable: false)]
    private string|null $parcelId = null;

    #[ManyToOne(targetEntity: Customer::class, inversedBy: 'parcels')]
    private Customer|null $customer = null;

    #[Column(name: 'description', type: Types::STRING, nullable: false)]
    private string $description = '';

    #[Column(name: 'dimensions', type: Types::STRING, nullable: false)]
    private string $dimensions = '';

    #[Column(name: 'weight', type: Types::STRING, nullable: false)]
    private string $weight = '';

    #[Column(name: 'delivery_service', type: Types::STRING, nullable: false)]
    private string $deliveryService = '';

    #[Column(name: 'supplier', type: Types::STRING, nullable: false)]
    private string $supplier = '';

    public function getParcelTrackingDetails(): ?ParcelTrackingDetails
    {
        return $this->parcelTrackingDetails;
    }

    public function setParcelTrackingDetails(?ParcelTrackingDetails $parcelTrackingDetails): void
    {
        $this->parcelTrackingDetails = $parcelTrackingDetails;
    }

    public function getParcelId(): ?string
    {
        return $this->parcelId;
    }

    public function setParcelId(?string $parcelId): void
    {
        $this->parcelId = $parcelId;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getDimensions(): string
    {
        return $this->dimensions;
    }

    public function setDimensions(string $dimensions): void
    {
        $this->dimensions = $dimensions;
    }

    public function getWeight(): string
    {
        return $this->weight;
    }

    public function setWeight(string $weight): void
    {
        $this->weight = $weight;
    }

    public function getDeliveryService(): string
    {
        return $this->deliveryService;
    }

    public function setDeliveryService(string $deliveryService): void
    {
        $this->deliveryService = $deliveryService;
    }

    public function getSupplier(): string
    {
        return $this->supplier;
    }

    public function setSupplier(string $supplier): void
    {
        $this->supplier = $supplier;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): void
    {
        $this->customer = $customer;
    }
}
