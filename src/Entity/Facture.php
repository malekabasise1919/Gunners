<?php

namespace App\Entity;

use App\Repository\FactureRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FactureRepository::class)
 */
class Facture
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\OneToOne(targetEntity=Contrat::class, inversedBy="facture", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $contrat;

    /**
     * @ORM\OneToOne(targetEntity=Transaction::class, mappedBy="facture", cascade={"persist", "remove"})
     */
    private $transaction;

    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->created_at;
    }

    public function setCreatedAt(): self
    {
        $this->created_at = new \Datetime();

        return $this;
    }

    public function getContrat(): ?contrat
    {
        return $this->contrat;
    }

    public function setContrat(contrat $contrat): self
    {
        $this->contrat = $contrat;

        return $this;
    }

    public function getTransaction(): ?Transaction
    {
        return $this->transaction;
    }

    public function setTransaction(?Transaction $transaction): self
    {
        // unset the owning side of the relation if necessary
        if ($transaction === null && $this->transaction !== null) {
            $this->transaction->setFacture(null);
        }

        // set the owning side of the relation if necessary
        if ($transaction !== null && $transaction->getFacture() !== $this) {
            $transaction->setFacture($this);
        }

        $this->transaction = $transaction;

        return $this;
    }
}
