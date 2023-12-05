<?php

namespace App\Entity;

use App\Entity\User;
use App\Entity\Moto;
use App\Entity\DepenseType;
use App\Controller\DepenseController;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\DepenseRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: DepenseRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(uriTemplate: '/api/depenses', controller: DepenseController::class, name: 'app_depenses_all'),
        new Post(uriTemplate: '/api/depenses', controller: DepenseController::class, denormalizationContext: ['groups' => ['depenses:write']], name: 'app_depenses_new'),
        new Get(uriTemplate: '/api/depenses/{id}', controller: DepenseController::class, denormalizationContext: ['groups' => ['depenses:read']], name: 'app_depenses_show'),
        new Delete(uriTemplate: '/api/depenses/{id}', controller: DepenseController::class, denormalizationContext: ['groups' => ['depenses:write']], name: 'app_depenses_delete'),
        new Patch(uriTemplate: '/api/depenses/{id}', controller: DepenseController::class, denormalizationContext: ['groups' => ['depenses:write']], name: 'app_depenses_edit'),
    ],
    formats: ["json"],
)]
class Depense
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['depenses:read', 'depenses:write'])]
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Groups(['depenses:read', 'depenses:write'])]
    private ?float $montant = null;

    #[ORM\ManyToOne(inversedBy: 'depenses')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['depenses:read'])]
    private ?user $user = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['depenses:read', 'depenses:write'])]
    private ?float $kmParcouru = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['depenses:read', 'depenses:write'])]
    private ?float $essenceConsomme = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['depenses:read'])]
    private ?float $consoMoyenne = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['depenses:read', 'depenses:write'])]
    private ?string $essenceType = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['depenses:read', 'depenses:write'])]
    private ?float $essencePrice = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['depenses:read', 'depenses:write'])]
    private ?\DateTimeInterface $date = null;

    #[ORM\ManyToOne(inversedBy: 'depenses')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['depenses:read', 'depenses:write'])]
    private ?moto $moto = null;

    #[ORM\ManyToOne(inversedBy: 'depenses')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['depenses:read', 'depenses:write'])]
    private ?depenseType $depenseType = null;

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
        return $this->kmParcouru;
    }

    public function setKmParcouru(?float $kmParcouru): static
    {
        $this->kmParcouru = $kmParcouru;

        return $this;
    }

    public function getEssenceConsomme(): ?float
    {
        return $this->essenceConsomme;
    }

    public function setEssenceConsomme(?float $essenceConsomme): static
    {
        $this->essenceConsomme = $essenceConsomme;

        return $this;
    }

    public function getConsoMoyenne(): ?float
    {
        return $this->consoMoyenne;
    }

    public function setConsoMoyenne(?float $consoMoyenne): static
    {
        $this->consoMoyenne = $consoMoyenne;

        return $this;
    }

    public function getEssenceType(): ?string
    {
        return $this->essenceType;
    }

    public function setEssenceType(?string $essenceType): static
    {
        $this->essenceType = $essenceType;

        return $this;
    }

    public function getEssencePrice(): ?float
    {
        return $this->essencePrice;
    }

    public function setEssencePrice(?float $essencePrice): static
    {
        $this->essencePrice = $essencePrice;

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
        return $this->depenseType;
    }

    public function setDepenseType(?depenseType $depenseType): static
    {
        $this->depenseType = $depenseType;

        return $this;
    }
}
