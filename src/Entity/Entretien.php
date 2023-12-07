<?php

namespace App\Entity;

use App\Entity\User;
use App\Entity\Moto;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Controller\EntretienController;
use App\Repository\EntretienRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: EntretienRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(uriTemplate: '/api/entretiens', controller: EntretienController::class, name: 'app_entretiens_all'),
        new Post(uriTemplate: '/api/entretiens', controller: EntretienController::class, denormalizationContext: ['groups' => ['entretiens:write']], name: 'app_entretiens_new'),
        new Get(uriTemplate: '/api/entretiens/{id}', controller: EntretienController::class, denormalizationContext: ['groups' => ['entretiens:read']], name: 'app_entretiens_show'),
        new Delete(uriTemplate: '/api/entretiens/{id}', controller: EntretienController::class, denormalizationContext: ['groups' => ['entretiens:write']], name: 'app_entretiens_delete'),
        new Patch(uriTemplate: '/api/entretiens/{id}', controller: EntretienController::class, denormalizationContext: ['groups' => ['entretiens:write']], name: 'app_entretiens_edit'),
    ],
    formats: ["json"],
)]
class Entretien
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['entretiens:read', 'entretiens:write'])]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['entretiens:read', 'entretiens:write'])]
    private ?bool $graissage = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['entretiens:read', 'entretiens:write'])]
    private ?bool $lavage = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['entretiens:read', 'entretiens:write'])]
    private ?float $pressionAv = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['entretiens:read', 'entretiens:write'])]
    private ?float $pressionAr = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['entretiens:read', 'entretiens:write'])]
    private ?\DateTimeInterface $date = null;

    #[ORM\ManyToOne(inversedBy: 'entretiens')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['entretiens:read', 'entretiens:write'])]
    private ?user $user = null;

    #[ORM\ManyToOne(inversedBy: 'entretiens')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['entretiens:read', 'entretiens:write'])]
    private ?moto $moto = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['entretiens:read', 'entretiens:write'])]
    private ?float $kilometrage = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGraissage(): ?bool
    {
        return $this->graissage;
    }

    public function setGraissage(?bool $graissage): static
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
        return $this->pressionAv;
    }

    public function setPressionAv(?float $pressionAv): static
    {
        $this->pressionAv = $pressionAv;

        return $this;
    }

    public function getPressionAr(): ?float
    {
        return $this->pressionAr;
    }

    public function setPressionAr(?float $pressionAr): static
    {
        $this->pressionAr = $pressionAr;

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

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(?user $user): static
    {
        $this->user = $user;

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

    public function getKilometrage(): ?float
    {
        return $this->kilometrage;
    }

    public function setKilometrage(?float $kilometrage): static
    {
        $this->kilometrage = $kilometrage;

        return $this;
    }
}
