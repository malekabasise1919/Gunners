<?php

namespace App\Entity;

use App\Repository\ReclamationRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReclamationRepository::class)
 */
class Reclamation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=Projet::class, inversedBy="reclamation", cascade={"persist", "remove"})
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $projet;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank (message="Cant be empty")
     * @Assert\Length(min="12", minMessage="La description doit contenir au minimum 12 caractères")
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_de_reclamation;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $statut;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProjet(): ?projet
    {
        return $this->projet;
    }

    public function setProjet(projet $projet): self
    {
        $this->projet = $projet;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDateDeReclamation(): ?\DateTimeInterface
    {
        return $this->date_de_reclamation;
    }

    public function setDateDeReclamation(): self
    {
        $this->date_de_reclamation = new \Datetime();

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;

        return $this;
    }
}
