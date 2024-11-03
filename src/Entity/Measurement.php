<?php

namespace App\Entity;

use App\Repository\MeasurementRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Location;

#[ORM\Entity(repositoryClass: MeasurementRepository::class)]
class Measurement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Location::class, inversedBy: "measurements")]
    #[ORM\JoinColumn(nullable: false)]
    private ?Location $location = null;

    #[ORM\Column(type: 'date')]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(type: 'decimal', precision: 3, scale: 0)]
    private ?float $celsius = null;

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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getCelsius(): ?float
    {
        return $this->celsius;
    }

    public function setCelsius(float $celsius): static
    {
        $this->celsius = $celsius;

        return $this;
    }
}
