<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Index;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;

use function sprintf;

#[Entity]
#[Table(name: 'customer')]
#[Index(
    name: "customer_details_idx",
    columns: [
        "first_name",
        "last_name",
        "address",
    ]
)]
class Customer
{
    #[Id]
    #[Column(type: Types::INTEGER)]
    #[GeneratedValue(strategy: 'AUTO')]
    private int|null $id = null;

    /**
     * Many customers have many parcels.
     *
     * @var Collection<int,Parcel>
     */
    #[OneToMany(targetEntity: Parcel::class, mappedBy: "customer")]
    #[JoinColumn(name: 'customer_id', referencedColumnName: 'id', unique: true, onDelete: 'CASCADE')]
    private Collection $parcels;

    #[Column(name: 'first_name', type: Types::STRING, nullable: false)]
    private string $firstName = '';

    #[Column(name: 'last_name', type: Types::STRING, nullable: false)]
    private string $lastName = '';

    #[Column(name: 'phone_number', type: Types::STRING, nullable: true)]
    private string $phoneNumber = '';

    #[Column(name: 'whatsapp_number', type: Types::STRING, nullable: true)]
    private string $whatsAppNumber = '';

    #[Column(name: 'email_address', type: Types::STRING, unique: true, nullable: true)]
    private string $emailAddress = '';

    #[Column(name: 'address', type: Types::STRING, nullable: false)]
    private string $address = '';

    public string $fullName {
        get => sprintf("%s %s", $this->firstName, $this->lastName);
    }

    public function __construct()
    {
        $this->parcels = new ArrayCollection();
    }

    /**
     * @return Collection<int, Parcel>
     */
    public function getParcels(): Collection
    {
        return $this->parcels;
    }

    /**
     * @param Collection<int,Parcel> $parcels
     */
    public function setParcels(Collection $parcels): void
    {
        $this->parcels = $parcels;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function setAddress(string $address): void
    {
        $this->address = $address;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }

    public function getWhatsAppNumber(): string
    {
        return $this->whatsAppNumber;
    }

    public function setWhatsAppNumber(string $whatsAppNumber): void
    {
        $this->whatsAppNumber = $whatsAppNumber;
    }

    public function getEmailAddress(): string
    {
        return $this->emailAddress;
    }

    public function setEmailAddress(string $emailAddress): void
    {
        $this->emailAddress = $emailAddress;
    }

}
