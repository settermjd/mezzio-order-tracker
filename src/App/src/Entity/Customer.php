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
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table(name: 'customer')]
class Customer
{
    #[Id]
    #[Column(type: Types::INTEGER)]
    #[GeneratedValue(strategy: 'IDENTITY')]
    private int|null $id = null;

    #[OneToMany(targetEntity: Parcel::class, mappedBy: 'customer')]
    private Collection $parcels;

    #[Column(name: 'first_name', type: Types::STRING, nullable: false)]
    private string $firstName = '' {
        get {
            return $this->firstName;
        }
        set {
            $this->firstName = $value;
        }
    }

    #[Column(name: 'last_name', type: Types::STRING, nullable: false)]
    private string $lastName = '' {
        get {
            return $this->lastName;
        }
        set {
            $this->lastName = $value;
        }
    }

    #[Column(name: 'address', type: Types::STRING, nullable: false)]
    private string $address = '' {
        get {
            return $this->address;
        }
        set {
            $this->address = $value;
        }
    }

    public function __construct()
    {
        $this->parcels = new ArrayCollection();
    }

    /**
     * @return Collection<int, Parcel>
     */
    public function getProducts(): Collection
    {
        return $this->parcels;
    }
}
