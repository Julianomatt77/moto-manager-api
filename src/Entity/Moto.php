<?php

namespace App\Entity;

use App\Entity\User;
use ApiPlatform\Action\NotFoundAction;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Controller\MotoController;
use App\Repository\MotoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: MotoRepository::class)]
#[Gedmo\SoftDeleteable(['deletedAt', false, false])]
#[ApiResource(operations: [
		new GetCollection(uriTemplate: '/api/motos', controller: MotoController::class, name: 'app_moto_all'),
        new GetCollection(uriTemplate: '/api/motos/deactivated', controller: MotoController::class, name: 'app_moto_deactivated'),
		new Post(uriTemplate: '/api/motos', controller: MotoController::class, denormalizationContext: ['groups' => ['moto:write']], name: 'app_moto_new'),
		new Get(uriTemplate: '/api/motos/{id}', controller: MotoController::class, denormalizationContext: ['groups' => ['moto:read']], name: 'app_moto_show'),
		new Delete(uriTemplate: '/api/motos/{id}', controller: MotoController::class, denormalizationContext: ['groups' => ['moto:write']], name: 'app_moto_delete'),
		new Patch(uriTemplate: '/api/motos/{id}', controller: MotoController::class, denormalizationContext: ['groups' => ['moto:write']], name: 'app_moto_edit'),
        new Patch(uriTemplate: '/api/motos/reactivate/{id}', controller: MotoController::class, denormalizationContext: ['groups' => ['moto:write']], name: 'app_moto_reactivate'),
	],
    formats: ["json"],
//	security:
//    normalizationContext: ['groups' => ['read']],
//    denormalizationContext: ['groups' => ['write']]
)]
class Moto
{
    use SoftDeleteableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
	#[Groups(['moto:read', 'moto:write', 'depenses:read', 'depenses:write', 'entretiens:read', 'entretiens:write'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
	#[Groups(['moto:read', 'moto:write', 'depenses:read', 'entretiens:read'])]
    private ?string $marque = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
	#[Groups(['moto:read', 'moto:write', 'depenses:read', 'entretiens:read'])]
    private ?string $modele = null;

    #[ORM\ManyToOne(inversedBy: 'motos')]
    #[ORM\JoinColumn(nullable: false)]
	#[Groups('moto:read')]
    private ?user $user = null;

    #[ORM\OneToMany(mappedBy: 'moto', targetEntity: Depense::class)]
    private Collection $depenses;

    #[ORM\OneToMany(mappedBy: 'moto', targetEntity: Entretien::class)]
    private Collection $entretiens;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['moto:read', 'moto:write'])]
    protected $deletedAt;

    public function __construct()
    {
        $this->depenses = new ArrayCollection();
        $this->entretiens = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMarque(): ?string
    {
        return $this->marque;
    }

    public function setMarque(string $marque): static
    {
        $this->marque = $marque;

        return $this;
    }

    public function getModele(): ?string
    {
        return $this->modele;
    }

    public function setModele(string $modele): static
    {
        $this->modele = $modele;

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

    /**
     * @return Collection<int, Depense>
     */
    public function getDepenses(): Collection
    {
        return $this->depenses;
    }

    public function addDepense(Depense $depense): static
    {
        if (!$this->depenses->contains($depense)) {
            $this->depenses->add($depense);
            $depense->setMoto($this);
        }

        return $this;
    }

    public function removeDepense(Depense $depense): static
    {
        if ($this->depenses->removeElement($depense)) {
            // set the owning side to null (unless already changed)
            if ($depense->getMoto() === $this) {
                $depense->setMoto(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Entretien>
     */
    public function getEntretiens(): Collection
    {
        return $this->entretiens;
    }

    public function addEntretien(Entretien $entretien): static
    {
        if (!$this->entretiens->contains($entretien)) {
            $this->entretiens->add($entretien);
            $entretien->setMoto($this);
        }

        return $this;
    }

    public function removeEntretien(Entretien $entretien): static
    {
        if ($this->entretiens->removeElement($entretien)) {
            // set the owning side to null (unless already changed)
            if ($entretien->getMoto() === $this) {
                $entretien->setMoto(null);
            }
        }

        return $this;
    }

    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;
    }
}
