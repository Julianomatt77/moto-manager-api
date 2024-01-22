<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Controller\MotoController;
use App\Controller\UserController;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Attributes\Examples;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ApiResource(
	operations: [
               		new Get(uriTemplate: '/api/users-infos', controller: UserController::class, denormalizationContext: ['groups' => ['user:read']], name: 'app_user_show'),
               //		new Patch(uriTemplate: '/api/users/{id}', controller: UserController::class, denormalizationContext: ['groups' => ['user:write']], name: 'app_user_edit'),
               //		new Delete(uriTemplate: '/api/users/{id}', controller: UserController::class, denormalizationContext: ['groups' => ['user:write']], name: 'app_user_delete'),
               //		new GetCollection(),
               		new Post(uriTemplate: '/api/register', controller: UserController::class, denormalizationContext: ['groups' => ['user:write']], name: 'api_register'),
               	],
	formats: ["json"],
)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['user:read', 'depenses:read', 'depenses:write', 'entretiens:read', 'entretiens:write', 'moto:read', 'moto:write'])]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
	#[Groups(['moto:read', 'user:write', 'user:read'])]
    private ?string $email = null;

    #[ORM\Column]
    #[Groups(['user:read'])]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
	#[Groups(['user:write'])]
    private ?string $password = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Depense::class)]
    private Collection $depenses;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Entretien::class)]
    private Collection $entretiens;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Moto::class)]
    private Collection $motos;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: DepenseType::class)]
    private Collection $depenseTypes;

    public function __construct()
    {
        $this->depenses = new ArrayCollection();
        $this->entretiens = new ArrayCollection();
        $this->motos = new ArrayCollection();
        $this->depenseTypes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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
            $depense->setUser($this);
        }

        return $this;
    }

    public function removeDepense(Depense $depense): static
    {
        if ($this->depenses->removeElement($depense)) {
            // set the owning side to null (unless already changed)
            if ($depense->getUser() === $this) {
                $depense->setUser(null);
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
            $entretien->setUser($this);
        }

        return $this;
    }

    public function removeEntretien(Entretien $entretien): static
    {
        if ($this->entretiens->removeElement($entretien)) {
            // set the owning side to null (unless already changed)
            if ($entretien->getUser() === $this) {
                $entretien->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Moto>
     */
    public function getMotos(): Collection
    {
        return $this->motos;
    }

    public function addMoto(Moto $moto): static
    {
        if (!$this->motos->contains($moto)) {
            $this->motos->add($moto);
            $moto->setUser($this);
        }

        return $this;
    }

    public function removeMoto(Moto $moto): static
    {
        if ($this->motos->removeElement($moto)) {
            // set the owning side to null (unless already changed)
            if ($moto->getUser() === $this) {
                $moto->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, DepenseType>
     */
    public function getDepenseTypes(): Collection
    {
        return $this->depenseTypes;
    }

    public function addDepenseType(DepenseType $depenseType): static
    {
        if (!$this->depenseTypes->contains($depenseType)) {
            $this->depenseTypes->add($depenseType);
            $depenseType->setUser($this);
        }

        return $this;
    }

    public function removeDepenseType(DepenseType $depenseType): static
    {
        if ($this->depenseTypes->removeElement($depenseType)) {
            // set the owning side to null (unless already changed)
            if ($depenseType->getUser() === $this) {
                $depenseType->setUser(null);
            }
        }

        return $this;
    }
}
