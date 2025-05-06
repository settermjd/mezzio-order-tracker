<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Customer;
use App\Entity\Parcel;
use App\Entity\ParcelTrackingDetails;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\Query\Parameter;

/**
 * @template-extends EntityRepository<Parcel>
 */
class ParcelRepository extends EntityRepository
{
    /**
     * Attempt to find parcel tracking details for a product by the tracking number, e.g., 99VBZ546012301000945604
     */
    public function findByTrackingNumber(string $trackingNumber): Parcel|null
    {
        $query = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('p')
            ->from(Parcel::class, 'p')
            ->where('ptd.trackingNumber = :trackingNumber')
            ->leftJoin(
                join: ParcelTrackingDetails::class,
                alias: 'ptd',
                conditionType: Join::WITH,
                condition: 'ptd.id = p.parcelTrackingDetails'
            )
            ->setParameter('trackingNumber', $trackingNumber);

        $parcel = $query->getQuery()
                    ->getOneOrNullResult();

        return $parcel instanceof Parcel
            ? $parcel
            : null;
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
