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
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;

use function md5;
use function mt_rand;
use function strtoupper;
use function substr;

readonly class ParcelService
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    /**
     * @param array<string,string> $parcelData
     * @throws ORMException
     * @throws OptimisticLockException
     */
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
     * addStatusUpdate adds a parcel status update for the parcel with the provided parcel tracking number
     *
     * If there is no parcel with the supplied tracking number, then a not found exception is thrown. Otherwise,
     * the parcel status update is recorded and a Parcel object is returned.
     *
     * @param array<string,string> $statusUpdateData
     * @throws EntityNotFoundException
     */
    public function addStatusUpdate(string $parcelTrackingNumber, array $statusUpdateData): Parcel|null
    {
        $parcelTrackingDetails = $this->entityManager
            ->getRepository(ParcelTrackingDetails::class)
            ->findOneBy([
                'trackingNumber' => $parcelTrackingNumber,
            ]);

        if (! $parcelTrackingDetails instanceof ParcelTrackingDetails) {
            throw new EntityNotFoundException(
                "Could not find parcel tracking details with tracking number {$parcelTrackingNumber}."
            );
        }

        $parcelStatusUpdate = new ParcelStatusUpdate();
        $parcelStatusUpdate->setDescription($statusUpdateData['description']);
        $parcelStatusUpdate->setAddress($statusUpdateData['address']);
        $parcelStatusUpdate->setCreatedAt(new DateTimeImmutable());

        $statusUpdates = $parcelTrackingDetails->getParcelStatusUpdates();
        $statusUpdates->add($parcelStatusUpdate);

        $this->entityManager->persist($parcelTrackingDetails);

        $this->entityManager->flush();

        return $parcelTrackingDetails->getParcel();
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
