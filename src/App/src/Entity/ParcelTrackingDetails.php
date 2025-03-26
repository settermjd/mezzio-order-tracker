<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table(name: 'parcel_tracking_details')]
class ParcelTrackingDetails
{
    #[Id]
    #[Column(type: Types::INTEGER)]
    #[GeneratedValue(strategy: 'IDENTITY')]
    private int|null $id = null;

    #[Column(name: 'tracking_id', type: Types::STRING, length: 24, nullable: false)]
    public string $trackingId = '' {
        set (string $trackingId) {
            $this->trackingId = $trackingId;
        }
    }

    #[OneToOne(targetEntity: Parcel::class, mappedBy: 'parcelTrackingDetails')]
    private Parcel|null $parcel = null;

    #[OneToMany(targetEntity: ParcelStatusUpdate::class, mappedBy: 'parcelTrackingDetails')]
    private Collection $parcelStatusUpdates {
        get {
            return $this->parcelStatusUpdates;
        }
    }

    public function __construct()
    {
        $this->parcelStatusUpdates = new ArrayCollection();
    }

}
