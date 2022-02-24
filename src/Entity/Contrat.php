<?php

namespace App\Entity;

use App\Repository\ContratRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=ContratRepository::class)
 */
class Contrat
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float", precision=10, scale=0)
     * * @Assert\NotEqualTo(
     *     value = 0,
     *     message = "Le prix d’un article ne doit pas être égal à  0 "
     * )
     */
    private $prix;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $created_at;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *      min = 5,
     *      max = 50,
     *      minMessage = "Le nom d'un article doit comporter au moins {{ limit }} caractères",
     *      maxMessage = "Le nom d'un article doit comporter au plus {{ limit }} caractères"
     * )
     */
    private $statut;

    /**
     * @ORM\OneToOne(targetEntity=Facture::class, mappedBy="contrat", cascade={"persist", "remove"})
     */
    private $facture;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="contrats")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user_client;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $user_freelancer;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

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

    public function getFacture(): ?Facture
    {
        return $this->facture;
    }

    public function setFacture(Facture $facture): self
    {
        // set the owning side of the relation if necessary
        if ($facture->getContrat() !== $this) {
            $facture->setContrat($this);
        }

        $this->facture = $facture;

        return $this;
    }

    public function getUserClient(): ?user
    {
        return $this->user_client;
    }

    public function setUserClient(?user $user_client): self
    {
        $this->user_client = $user_client;

        return $this;
    }

    public function getUserFreelancer(): ?user
    {
        return $this->user_freelancer;
    }

    public function setUserFreelancer(?user $user_freelancer): self
    {
        $this->user_freelancer = $user_freelancer;

        return $this;
    }
}
