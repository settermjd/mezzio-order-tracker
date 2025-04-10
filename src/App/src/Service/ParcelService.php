<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Customer;
use App\Entity\Parcel;
use App\Entity\ParcelStatusUpdate;
use App\Entity\ParcelTrackingDetails;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Laminas\EventManager\Event;

use function md5;
use function mt_rand;
use function strtoupper;
use function substr;

readonly class ParcelService
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function addParcel(array $parcelData): Parcel
    {
        $customer = $this->entityManager->find(Customer::class, $parcelData['customer']);

        $parcel = new Parcel();
        $parcel->setCustomer($customer);
        $parcel->setParcelId($this->generateTrackingNumber(12));
        $parcel->setDescription($parcelData['description']);
        $parcel->setDeliveryService($parcelData['deliveryService']);
        $parcel->setDimensions($parcelData['dimensions']);
        $parcel->setSupplier($parcelData['supplier']);
        $parcel->setWeight($parcelData['weight']);

        $parcelStatus = new ParcelStatusUpdate();
        $parcelStatus->setDescription("Shipping information received");
        $parcelStatus->setCreatedAt(new DateTimeImmutable());

        $parcelTrackingDetails = new ParcelTrackingDetails();
        $parcelTrackingDetails->setParcel($parcel);
        $parcelTrackingDetails->setTrackingNumber($this->generateTrackingNumber(23));
        $parcelTrackingDetails->setParcelStatusUpdates(
            new ArrayCollection([
                $parcelStatus,
            ])
        );

        $parcel->setParcelTrackingDetails($parcelTrackingDetails);

        $this->entityManager->persist($parcel);
        $this->entityManager->persist($parcelTrackingDetails);

        $this->entityManager->flush();

        return $parcel;
    }

    /**
     * Generates and returns a new tracking number, random string of a given length specified by $length.
     */
    private function generateTrackingNumber(int $length): string
    {
        return strtoupper(
            substr(
                string: md5((string) mt_rand()),
                offset: 0,
                length: $length
            )
        );
    }
}
