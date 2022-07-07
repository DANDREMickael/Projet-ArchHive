<?php

namespace App\Entity;

use App\Repository\AuteurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AuteurRepository::class)]
class Auteur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 32)]
    private $nom_auteur;

    #[ORM\Column(type: 'string', length: 32)]
    private $prenom_auteur;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $biographie;

    #[ORM\OneToMany(mappedBy: 'auteur', targetEntity: Livre::class)]
    private $livres;

    public function __construct()
    {
        $this->livres = new ArrayCollection();
    }

    public function getNomAuteur(): ?string
    {
        return $this->nom_auteur;
    }

    public function setNomAuteur(string $nom_auteur): self
    {
        $this->nom_auteur = $nom_auteur;

        return $this;
    }

    public function getPrenomAuteur(): ?string
    {
        return $this->prenom_auteur;
    }

    public function setPrenomAuteur(string $prenom_auteur): self
    {
        $this->prenom_auteur = $prenom_auteur;

        return $this;
    }

    public function getBiographie(): ?string
    {
        return $this->biographie;
    }

    public function setBiographie(?string $biographie): self
    {
        $this->biographie = $biographie;

        return $this;
    }

    /**
     * @return Collection<int, Livre>
     */
    public function getLivres(): Collection
    {
        return $this->livres;
    }

    public function addLivre(Livre $livre): self
    {
        if (!$this->livres->contains($livre)) {
            $this->livres[] = $livre;
            $livre->setAuteur($this);
        }

        return $this;
    }

    public function removeLivre(Livre $livre): self
    {
        if ($this->livres->removeElement($livre)) {
            // set the owning side to null (unless already changed)
            if ($livre->getAuteur() === $this) {
                $livre->setAuteur(null);
            }
        }

        return $this;
    }
}
