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

    #[ORM\Column(type: 'integer')]
    private $id_livre;

    #[ORM\Column(type: 'string', length: 255)]
    private $titre;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $description_livre;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $date_parution;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdLivre(): ?int
    {
        return $this->id_livre;
    }

    public function setIdLivre(int $id_livre): self
    {
        $this->id_livre = $id_livre;

        return $this;
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
}
