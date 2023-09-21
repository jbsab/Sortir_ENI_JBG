<?php

namespace App\Entity;

use App\Repository\FiltersSortiesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FiltersSortiesRepository::class)]
class FiltersSorties
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::OBJECT)]
    private ?object $campus = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $nomSortie = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateDebut = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateFin = null;

    #[ORM\Column(nullable: true)]
    private ?bool $organisateur = null;

    #[ORM\Column(nullable: true)]
    private ?bool $inscrit = null;

    #[ORM\Column(nullable: true)]
    private ?bool $pasInscrit = null;

    #[ORM\Column(nullable: true)]
    private ?bool $passees = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCampus(): ?object
    {
        return $this->campus;
    }

    public function setCampus(object $campus): static
    {
        $this->campus = $campus;

        return $this;
    }

    public function getNomSortie(): ?string
    {
        return $this->nomSortie;
    }

    public function setNomSortie(?string $nomSortie): static
    {
        $this->nomSortie = $nomSortie;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(?\DateTimeInterface $dateDebut): static
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(?\DateTimeInterface $dateFin): static
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function isOrganisateur(): ?bool
    {
        return $this->organisateur;
    }

    public function setOrganisateur(?bool $organisateur): static
    {
        $this->organisateur = $organisateur;

        return $this;
    }

    public function isInscrit(): ?bool
    {
        return $this->inscrit;
    }

    public function setInscrit(?bool $inscrit): static
    {
        $this->inscrit = $inscrit;

        return $this;
    }

    public function isPasInscrit(): ?bool
    {
        return $this->pasInscrit;
    }

    public function setPasInscrit(?bool $pasInscrit): static
    {
        $this->pasInscrit = $pasInscrit;

        return $this;
    }

    public function isPassees(): ?bool
    {
        return $this->passees;
    }

    public function setPassees(?bool $passees): static
    {
        $this->passees = $passees;

        return $this;
    }
}
