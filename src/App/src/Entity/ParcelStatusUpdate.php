<?php

declare(strict_types=1);

namespace App\Entity;

use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Index;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table(name: 'parcel_status_update')]
#[Index(
    name: "desc_address_created_idx",
    columns: [
        "description",
        "address",
        "created_at",
    ]
)]
class ParcelStatusUpdate
{
    #[Id]
    #[Column(type: Types::INTEGER)]
    #[GeneratedValue(strategy: 'IDENTITY')]
    private int|null $id = null;

    #[Column(name: 'description', type: Types::STRING, nullable: false)]
    private string $description = '';

    #[Column(name: 'address', type: Types::STRING, nullable: false)]
    private string $address = '';

    #[Column(
        name: 'created_at',
        type: Types::DATETIMETZ_IMMUTABLE,
        nullable: false,
        options: ["default" => "DATE(CURRENT_DATE, 'localtime')"]
    )]
    private DateTimeImmutable $createdAt {
        get => $this->createdAt;
        set {
            $this->createdAt = $value;
        }
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function setAddress(string $address): void
    {
        $this->address = $address;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
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
