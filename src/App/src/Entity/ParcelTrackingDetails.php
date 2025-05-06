<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ParcelTrackingDetailsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Index;
use Doctrine\ORM\Mapping\InverseJoinColumn;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity(repositoryClass: ParcelTrackingDetailsRepository::class)]
#[Table(name: 'parcel_tracking_details')]
#[Index(
    name: "tracking_number_parcel_idx",
    columns: [
        "tracking_number",
    ]
)]
class ParcelTrackingDetails
{
    #[Id]
    #[Column(type: Types::INTEGER)]
    #[GeneratedValue(strategy: 'AUTO')]
    private int|null $id = null;

    #[Column(name: 'tracking_number', type: Types::STRING, length: 24, unique: true, nullable: false)]
    public string $trackingNumber = '';

    #[OneToOne(targetEntity: Parcel::class, mappedBy: 'parcelTrackingDetails')]
    private Parcel|null $parcel = null;

    /**
     * Many parcel tracking details have many parcel status updates.
     *
     * @var Collection<int, ParcelStatusUpdate>|null $parcelStatusUpdates
     */
    #[JoinTable(name: 'tracking_status_updates')]
    #[JoinColumn(name: 'tracking_id', referencedColumnName: 'id', onDelete: 'RESTRICT')]
    #[InverseJoinColumn(name: 'status_update_id', referencedColumnName: 'id', unique: true, onDelete: 'CASCADE')]
    #[ManyToMany(targetEntity: ParcelStatusUpdate::class, cascade: ['persist'], orphanRemoval: true)]
    private Collection|null $parcelStatusUpdates;

    public function __construct()
    {
        $this->parcelStatusUpdates = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getTrackingNumber(): string
    {
        return $this->trackingNumber;
    }

    public function setTrackingNumber(string $trackingNumber): void
    {
        $this->trackingNumber = $trackingNumber;
    }

    public function getParcel(): ?Parcel
    {
        return $this->parcel;
    }

    public function setParcel(?Parcel $parcel): void
    {
        $this->parcel = $parcel;
    }

    /**
     * @return Collection<int, ParcelStatusUpdate>
     */
    public function getParcelStatusUpdates(): Collection
    {
        return $this->parcelStatusUpdates;
    }

    /**
     * @param Collection<int,ParcelStatusUpdate>|null $parcelStatusUpdates
     */
    public function setParcelStatusUpdates(?Collection $parcelStatusUpdates): void
    {
        $this->parcelStatusUpdates = $parcelStatusUpdates;
    }
}
