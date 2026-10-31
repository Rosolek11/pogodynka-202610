<?php

namespace App\Entity;

use App\Repository\LocationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LocationRepository::class)]
#[ORM\Table(name: 'miejscowosc')]
class Location
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_miejscowosci')]
    private ?int $id = null;

    #[ORM\Column(name: 'nazwa', length: 255, unique: true)]
    private ?string $city = null;

    #[ORM\Column(name: 'szerokosc_geo', type: Types::DECIMAL, precision: 10, scale: 7)]
    private ?string $latitude = null;

    #[ORM\Column(name: 'dlugosc_geo', type: Types::DECIMAL, precision: 10, scale: 7)]
    private ?string $longitude = null;

    /**
     * @var Collection<int, Measurement>
     */
    #[ORM\OneToMany(targetEntity: Measurement::class, mappedBy: 'location')]
    private Collection $measurements;

    public function __construct()
    {
        $this->measurements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLatitude(?string $latitude): static
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(?string $longitude): static
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * @return Collection<int, Measurement>
     */
    public function getMeasurements(): Collection
    {
        return $this->measurements;
    }

    public function addMeasurement(Measurement $measurement): static
    {
        if (!$this->measurements->contains($measurement)) {
            $this->measurements->add($measurement);
            $measurement->setLocation($this);
        }

        return $this;
    }

    public function removeMeasurement(Measurement $measurement): static
    {
        if ($this->measurements->removeElement($measurement)) {
            // set the owning side to null (unless already changed)
            if ($measurement->getLocation() === $this) {
                $measurement->setLocation(null);
            }
        }

        return $this;
    }
}
