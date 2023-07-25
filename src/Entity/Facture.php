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
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $MHT;

    /**
     * @ORM\Column(type="float")
     */
    private $MTTC;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateFacture;

    /**
     * @ORM\Column(type="float")
     */
    private $tva;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMHT(): ?float
    {
        return $this->MHT;
    }

    public function setMHT(float $MHT): self
    {
        $this->MHT = $MHT;

        return $this;
    }

    public function getMTTC(): ?float
    {
        return $this->MTTC;
    }

    public function setMTTC(float $MTTC): self
    {
        $this->MTTC = $MTTC;

        return $this;
    }

    public function getDateFacture(): ?\DateTimeInterface
    {
        return $this->dateFacture;
    }

    public function setDateFacture(\DateTimeInterface $dateFacture): self
    {
        $this->dateFacture = $dateFacture;

        return $this;
    }

    public function getTva(): ?float
    {
        return $this->tva;
    }

    public function setTva(float $tva): self
    {
        $this->tva = $tva;

        return $this;
    }
}
