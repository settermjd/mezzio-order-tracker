<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\ParcelTrackingDetails;
use Doctrine\ORM\EntityRepository;

/**
 * @template-extends EntityRepository<ParcelTrackingDetails>
 */
class ParcelTrackingDetailsRepository extends EntityRepository
{
}
