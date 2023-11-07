<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\EntretienRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EntretienRepository::class)]
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
class Entretien
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $graissage = null;

    #[ORM\Column(nullable: true)]
    private ?bool $lavage = null;

    #[ORM\Column(nullable: true)]
    private ?float $pression_av = null;

    #[ORM\Column(nullable: true)]
    private ?float $pression_ar = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

//    #[ORM\ManyToOne(inversedBy: 'entretiens')]
//    #[ORM\JoinColumn(nullable: false)]
//    private ?user $user = null;
//
//    #[ORM\ManyToOne(inversedBy: 'entretiens')]
//    #[ORM\JoinColumn(nullable: false)]
//    private ?moto $moto = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGraissage(): ?int
    {
        return $this->graissage;
    }

    public function setGraissage(?int $graissage): static
    {
        $this->graissage = $graissage;

        return $this;
    }

    public function isLavage(): ?bool
    {
        return $this->lavage;
    }

    public function setLavage(?bool $lavage): static
    {
        $this->lavage = $lavage;

        return $this;
    }

    public function getPressionAv(): ?float
    {
        return $this->pression_av;
    }

    public function setPressionAv(?float $pression_av): static
    {
        $this->pression_av = $pression_av;

        return $this;
    }

    public function getPressionAr(): ?float
    {
        return $this->pression_ar;
    }

    public function setPressionAr(?float $pression_ar): static
    {
        $this->pression_ar = $pression_ar;

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

//    public function getUser(): ?user
//    {
//        return $this->user;
//    }
//
//    public function setUser(?user $user): static
//    {
//        $this->user = $user;
//
//        return $this;
//    }
//
//    public function getMoto(): ?moto
//    {
//        return $this->moto;
//    }
//
//    public function setMoto(?moto $moto): static
//    {
//        $this->moto = $moto;
//
//        return $this;
//    }
}
