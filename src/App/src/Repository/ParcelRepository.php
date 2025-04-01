<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Customer;
use App\Entity\ParcelTrackingDetails;
use Doctrine\ORM\EntityRepository;

class ParcelRepository extends EntityRepository
{
    /**
     * Attempt to find parcel tracking details for a product by the tracking number, e.g., 99VBZ546012301000945604
     */
    public function findTrackingDetailsByTrackingNumber(string $trackingNumber): ?ParcelTrackingDetails
    {
        $query = $this->getEntityManager()
            ->createQuery(
                "SELECT ptd 
                FROM App\Entity\Parcel p 
                INNER JOIN App\Entity\ParcelTrackingDetails ptd
                WHERE ptd.trackingNumber = :trackingNumber"
            )->setParameter('trackingNumber', $trackingNumber);

        return $query->getOneOrNullResult();
    }

    public function findCustomer(): ?Customer
    {
        $query = $this->getEntityManager()
            ->createQuery(
                "SELECT c 
                FROM App\Entity\Parcel p 
                INNER JOIN App\Entity\ParcelTrackingDetails ptd
                WHERE ptd.trackingNumber = :trackingNumber"
            );

        return $query->getOneOrNullResult();
    }
}
