<?php

namespace App\Entity;

use App\Repository\VolRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VolRepository::class)]
class Vol
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nomDeLaDestination = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomDeLaDestination(): ?string
    {
        return $this->nomDeLaDestination;
    }

    public function setNomDeLaDestination(string $nomDeLaDestination): static
    {
        $this->nomDeLaDestination = $nomDeLaDestination;

        return $this;
    }
}
