<?php

namespace App\Entity;

use App\Repository\MeasurementRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MeasurementRepository::class)]
#[ORM\Table(name: 'dane_meteorologiczne')]
class Measurement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_wpisu')]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'measurements')]
    #[ORM\JoinColumn(name: 'id_miejscowosci', referencedColumnName: 'id_miejscowosci', nullable: false)]
    private ?Location $location = null;

    #[ORM\Column(name: 'data', type: Types::DATE_MUTABLE)]
    private ?\DateTime $date = null;

    #[ORM\Column(name: 'temperatura_C', type: Types::DECIMAL, precision: 4, scale: 1)]
    private ?string $celsius = null;

    #[ORM\Column(name: 'wilgotnosc', type: Types::SMALLINT)]
    private ?int $humidity = null;

    #[ORM\Column(name: 'cisnienie', type: Types::DECIMAL, precision: 6, scale: 1)]
    private ?string $pressure = null;

    #[ORM\Column(name: 'opady', type: Types::DECIMAL, precision: 6, scale: 1)]
    private ?string $precipitation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(?Location $location): static
    {
        $this->location = $location;

        return $this;
    }

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getCelsius(): ?string
    {
        return $this->celsius;
    }

    public function setCelsius(string $celsius): static
    {
        $this->celsius = $celsius;

        return $this;
    }

    public function getHumidity(): ?int
    {
        return $this->humidity;
    }

    public function setHumidity(int $humidity): static
    {
        $this->humidity = $humidity;

        return $this;
    }

    public function getPressure(): ?string
    {
        return $this->pressure;
    }

    public function setPressure(string $pressure): static
    {
        $this->pressure = $pressure;

        return $this;
    }

    public function getPrecipitation(): ?string
    {
        return $this->precipitation;
    }

    public function setPrecipitation(string $precipitation): static
    {
        $this->precipitation = $precipitation;

        return $this;
    }
}
