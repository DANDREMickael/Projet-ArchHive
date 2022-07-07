<?php

namespace App\Entity;

use App\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'Cet email est déjà utilisé')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private $email;

    #[ORM\Column(type: 'json')]
    private $roles = [];

    #[ORM\Column(type: 'string')]
    private $password;

    private $newPassword;

    #[ORM\Column(type: 'string', length: 16, nullable: true)]
    private $nom;

    #[ORM\Column(type: 'string', length: 16, nullable: true)]
    private $prenom;

    #[ORM\Column(type: 'datetime')]
    private $dateNaissance;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $adresse;

    #[ORM\Column(type: 'string', nullable: true)]
    private $codePostal;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $ville;

    #[ORM\Column(type: 'boolean')]
    private $cgu;

    #[ORM\Column(type: 'datetime')]
    private $createdAt;

    #[ORM\Column(type: 'string', length: 255)]
    private $image;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: DemandeEmprunt::class)]
    private $demande_emprunt;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Emprunt::class)]
    private $emprunt;

    public function __construct()
    {
        $this->setCreatedAt();
        $this->demande_emprunt = new ArrayCollection();
        $this->emprunt = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getFullname(): ?string
    {
        return $this->nom . ' ' . $this->prenom;
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
        // Permet dde s'arrurer que tous les utilisateurs ont au moins le ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
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

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getNewPassword(): ?string
    {
        return $this->newPassword;
    }

    public function setNewPassword(string $newPassword): self
    {
        $this->newPassword = $newPassword;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // Si des données temporaires ou sensibles de l'utilisateur sont stockées, les effacer ici
        // $this->plainPassword = null;
    }

    public function getDateNaissance(): ?\DateTimeInterface
    {
        return $this->dateNaissance;
    }

    public function setDateNaissance(\DateTimeInterface $dateNaissance): self
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getCodePostal(): ?string
    {
        return $this->codePostal;
    }

    public function setCodePostal(string $codePostal): self
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function isCgu(): ?bool
    {
        return $this->cgu;
    }

    public function setCgu(bool $cgu): self
    {
        $this->cgu = $cgu;

        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(): self
    {
        $this->createdAt = new \DateTime();

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection<int, DemandeEmprunt>
     */
    public function getDemandeEmprunt(): Collection
    {
        return $this->demande_emprunt;
    }

    public function addDemandeEmprunt(DemandeEmprunt $demandeEmprunt): self
    {
        if (!$this->demande_emprunt->contains($demandeEmprunt)) {
            $this->demande_emprunt[] = $demandeEmprunt;
            $demandeEmprunt->setUser($this);
        }

        return $this;
    }

    public function removeDemandeEmprunt(DemandeEmprunt $demandeEmprunt): self
    {
        if ($this->demande_emprunt->removeElement($demandeEmprunt)) {
            // set the owning side to null (unless already changed)
            if ($demandeEmprunt->getUser() === $this) {
                $demandeEmprunt->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Emprunt>
     */
    public function getEmprunt(): Collection
    {
        return $this->emprunt;
    }

    public function addEmprunt(Emprunt $emprunt): self
    {
        if (!$this->emprunt->contains($emprunt)) {
            $this->emprunt[] = $emprunt;
            $emprunt->setUser($this);
        }

        return $this;
    }

    public function removeEmprunt(Emprunt $emprunt): self
    {
        if ($this->emprunt->removeElement($emprunt)) {
            // set the owning side to null (unless already changed)
            if ($emprunt->getUser() === $this) {
                $emprunt->setUser(null);
            }
        }

        return $this;
    }
}