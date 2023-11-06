<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\DepenseRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DepenseRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new Patch(),
        new Delete(),
        new GetCollection(),
        new Post(),
    ],
    formats: ["json"],
)]
class Depense
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $montant = null;

    #[ORM\ManyToOne(inversedBy: 'depenses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?user $user = null;

    #[ORM\Column(nullable: true)]
    private ?float $km_parcouru = null;

    #[ORM\Column(nullable: true)]
    private ?float $essence_consomme = null;

    #[ORM\Column(nullable: true)]
    private ?float $conso_moyenne = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $essence_type = null;

    #[ORM\Column(nullable: true)]
    private ?float $essence_price = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\ManyToOne(inversedBy: 'depenses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?moto $moto = null;

    #[ORM\ManyToOne(inversedBy: 'depenses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?depenseType $depense_type = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): static
    {
        $this->montant = $montant;

        return $this;
    }

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(?user $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getKmParcouru(): ?float
    {
        return $this->km_parcouru;
    }

    public function setKmParcouru(?float $km_parcouru): static
    {
        $this->km_parcouru = $km_parcouru;

        return $this;
    }

    public function getEssenceConsomme(): ?float
    {
        return $this->essence_consomme;
    }

    public function setEssenceConsomme(?float $essence_consomme): static
    {
        $this->essence_consomme = $essence_consomme;

        return $this;
    }

    public function getConsoMoyenne(): ?float
    {
        return $this->conso_moyenne;
    }

    public function setConsoMoyenne(?float $conso_moyenne): static
    {
        $this->conso_moyenne = $conso_moyenne;

        return $this;
    }

    public function getEssenceType(): ?string
    {
        return $this->essence_type;
    }

    public function setEssenceType(?string $essence_type): static
    {
        $this->essence_type = $essence_type;

        return $this;
    }

    public function getEssencePrice(): ?float
    {
        return $this->essence_price;
    }

    public function setEssencePrice(?float $essence_price): static
    {
        $this->essence_price = $essence_price;

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

    public function getMoto(): ?moto
    {
        return $this->moto;
    }

    public function setMoto(?moto $moto): static
    {
        $this->moto = $moto;

        return $this;
    }

    public function getDepenseType(): ?depenseType
    {
        return $this->depense_type;
    }

    public function setDepenseType(?depenseType $depense_type): static
    {
        $this->depense_type = $depense_type;

        return $this;
    }
}
