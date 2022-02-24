<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Moyenpaiement
 *
 * @ORM\Table(name="moyenpaiement")
 * @ORM\Entity
 */
class Moyenpaiement
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_moyen_paiement", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idMoyenPaiement;

    /**
     * @var string|null
     *
     * @ORM\Column(name="type", type="string", length=50, nullable=true)
     */
    private $type;

    public function getIdMoyenPaiement(): ?int
    {
        return $this->idMoyenPaiement;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }


}
