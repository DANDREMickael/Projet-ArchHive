<?php

namespace App\Entity;

use App\Repository\EmpruntRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmpruntRepository::class)]
class Emprunt
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'datetime')]
    private $date_debut_emprunt;

    #[ORM\Column(type: 'datetime')]
    private $date_fin_emprunt;

    #[ORM\Column(type: 'boolean')]
    private $statut;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDebutEmprunt(): ?\DateTimeInterface
    {
        return $this->date_debut_emprunt;
    }

    public function setDateDebutEmprunt(\DateTimeInterface $date_debut_emprunt): self
    {
        $this->date_debut_emprunt = $date_debut_emprunt;

        return $this;
    }

    public function getDateFinEmprunt(): ?\DateTimeInterface
    {
        return $this->date_fin_emprunt;
    }

    public function setDateFinEmprunt(\DateTimeInterface $date_fin_emprunt): self
    {
        $this->date_fin_emprunt = $date_fin_emprunt;

        return $this;
    }

    public function isStatut(): ?bool
    {
        return $this->statut;
    }

    public function setStatut(bool $statut): self
    {
        $this->statut = $statut;

        return $this;
    }
}
