<?php

namespace App\Entity;

use App\Repository\DestinationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DestinationRepository::class)]
class Destination
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nomDeLaDestination = null;

    #[ORM\OneToMany(targetEntity: VilleImage::class, mappedBy: 'destination', cascade:['persist'])]
    private Collection $villeImage;

    public function __construct()
    {
        $this->villeImage = new ArrayCollection();
    }

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

    /**
     * @return Collection<int, VilleImage>
     */
    public function getVilleImage(): Collection
    {
        return $this->villeImage;
    }

    public function addVilleImage(VilleImage $villeImage): static
    {
        if (!$this->villeImage->contains($villeImage)) {
            $this->villeImage->add($villeImage);
            $villeImage->setDestination($this);
        }

        return $this;
    }

    public function removeVilleImage(VilleImage $villeImage): static
    {
        if ($this->villeImage->removeElement($villeImage)) {
            // set the owning side to null (unless already changed)
            if ($villeImage->getDestination() === $this) {
                $villeImage->setDestination(null);
            }
        }

        return $this;
    }
}
