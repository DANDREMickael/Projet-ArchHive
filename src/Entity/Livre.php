<?php

namespace App\Entity;

use App\Repository\LivreRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LivreRepository::class)]
class Livre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $titre;

    #[ORM\Column(type: 'string', length: 3600, nullable: true)]
    private $description_livre;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $date_parution;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Assert\File(mimeTypes: 'image/jpeg')]
    private $image;

    #[ORM\ManyToOne(targetEntity: Auteur::class, inversedBy: 'livres')]
    private $auteur;

    #[ORM\ManyToOne(targetEntity: Genre::class, inversedBy: 'livres')]
    private $genre;

    #[ORM\ManyToOne(targetEntity: Stock::class, inversedBy: 'livres')]
    private $stock;

    #[ORM\OneToOne(targetEntity: DemandeEmprunt::class, cascade: ['persist', 'remove'])]
    private $demande_emprunt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescriptionLivre(): ?string
    {
        return $this->description_livre;
    }

    public function setDescriptionLivre(?string $description_livre): self
    {
        $this->description_livre = $description_livre;

        return $this;
    }

    public function getDateParution(): ?\DateTimeInterface
    {
        return $this->date_parution;
    }

    public function setDateParution(?\DateTimeInterface $date_parution): self
    {
        $this->date_parution = $date_parution;

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

    public function getAuteur(): ?Auteur
    {
        return $this->auteur;
    }

    public function setAuteur(?Auteur $auteur): self
    {
        $this->auteur = $auteur;

        return $this;
    }

    public function getGenre(): ?Genre
    {
        return $this->genre;
    }

    public function setGenre(?Genre $genre): self
    {
        $this->genre = $genre;

        return $this;
    }

    public function getStock(): ?Stock
    {
        return $this->stock;
    }

    public function setStock(?Stock $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    public function getDemandeEmprunt(): ?DemandeEmprunt
    {
        return $this->demande_emprunt;
    }

    public function setDemandeEmprunt(?DemandeEmprunt $demande_emprunt): self
    {
        $this->demande_emprunt = $demande_emprunt;

        return $this;
    }
}
