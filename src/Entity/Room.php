<?php

namespace App\Entity;

use App\Repository\RoomRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: RoomRepository::class)]
class Room {

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    #[ORM\Column]
    private string $publicId;

    public function __construct(
        #[ORM\Column(length: 200)]
        private readonly string $name,
        #[ORM\ManyToOne]
        #[ORM\JoinColumn(nullable: false)]
        private readonly User   $owner,
    ) {

        $this->publicId = Uuid::v4();
    }

    public function getPublicId()
    : string {

        return $this->publicId;
    }

    public function getName()
    : string {

        return $this->name;
    }

    public function getOwner()
    : string {

        return $this->owner->getUserIdentifier();
    }

    public function belongsToUser(User $user)
    : bool {

        return $this->owner === $user;
    }
}
