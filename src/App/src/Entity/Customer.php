<?php

namespace App\Entity;

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
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table(name: 'customer')]
#[Index(
    name: "customer_details_idx",
    columns: [
        "first_name",
        "last_name",
        "address"
    ]
)]
class Customer
{
    #[Id]
    #[Column(type: Types::INTEGER)]
    #[GeneratedValue(strategy: 'IDENTITY')]
    private int|null $id = null;

    /**
     * Many customers have many parcels.
     * @var Collection<int, Parcel>
     */
    #[JoinTable(name: 'customer_parcels')]
    #[JoinColumn(name: 'customer_id', referencedColumnName: 'id')]
    #[InverseJoinColumn(name: 'parcel_id', referencedColumnName: 'id', unique: true)]
    #[ManyToMany(targetEntity: Parcel::class)]
    private Collection|null $parcels = null;

    #[Column(name: 'first_name', type: Types::STRING, nullable: false)]
    private string $firstName = '';

    #[Column(name: 'last_name', type: Types::STRING, nullable: false)]
    private string $lastName = '';

    #[Column(name: 'address', type: Types::STRING, nullable: false)]
    private string $address = '';

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


}
