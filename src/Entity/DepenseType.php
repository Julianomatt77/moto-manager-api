<?php

namespace App\Entity;

use App\Controller\DepenseTypeController;
use App\Entity\User;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\DepenseTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: DepenseTypeRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(uriTemplate: '/api/depensesTypes', controller: DepenseTypeController::class, name: 'app_depensesTypes_all'),
        new Post(uriTemplate: '/api/depensesTypes', controller: DepenseTypeController::class, denormalizationContext: ['groups' => ['depensesTypes:write']], name: 'app_depensesTypes_new'),
        new Get(uriTemplate: '/api/depensesTypes/{id}', controller: DepenseTypeController::class, denormalizationContext: ['groups' => ['depensesTypes:read']], name: 'app_depensesTypes_show'),
        new Delete(uriTemplate: '/api/depensesTypes/{id}', controller: DepenseTypeController::class, denormalizationContext: ['groups' => ['depensesTypes:write']], name: 'app_depensesTypes_delete'),
        new Patch(uriTemplate: '/api/depensesTypes/{id}', controller: DepenseTypeController::class, denormalizationContext: ['groups' => ['depensesTypes:write']], name: 'app_depensesTypes_edit'),
    ],
    formats: ["json"],
)]
class DepenseType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['depensesTypes:read', 'depensesTypes:write'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['depensesTypes:read', 'depensesTypes:write'])]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'depenseType', targetEntity: Depense::class)]
    #[Groups(['depensesTypes:read'])]
    private Collection $depenses;

    #[ORM\ManyToOne(inversedBy: 'depenseTypes')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['depensesTypes:read'])]
    private ?User $user = null;

    public function __construct()
    {
        $this->depenses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

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
            $depense->setDepenseType($this);
        }

        return $this;
    }

    public function removeDepense(Depense $depense): static
    {
        if ($this->depenses->removeElement($depense)) {
            // set the owning side to null (unless already changed)
            if ($depense->getDepenseType() === $this) {
                $depense->setDepenseType(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
