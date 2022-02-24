<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Adresse
 *
 * @ORM\Table(name="adresse")
 * @ORM\Entity
 */
class Adresse
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_adresse", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idAdresse;

    /**
     * @var int
     *
     * @ORM\Column(name="nb_rue", type="integer", nullable=false)
     */
    private $nbRue;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_rue", type="string", length=50, nullable=false)
     */
    private $nomRue;

    /**
     * @var string
     *
     * @ORM\Column(name="code_postal", type="string", length=5, nullable=false)
     */
    private $codePostal;

    /**
     * @var string
     *
     * @ORM\Column(name="ville_nom", type="string", length=50, nullable=false)
     */
    private $villeNom;

    /**
     * @var string|null
     *
     * @ORM\Column(name="complement", type="string", length=100, nullable=true)
     */
    private $complement;

    public function getIdAdresse(): ?int
    {
        return $this->idAdresse;
    }

    public function getNbRue(): ?int
    {
        return $this->nbRue;
    }

    public function setNbRue(int $nbRue): self
    {
        $this->nbRue = $nbRue;

        return $this;
    }

    public function getNomRue(): ?string
    {
        return $this->nomRue;
    }

    public function setNomRue(string $nomRue): self
    {
        $this->nomRue = $nomRue;

        return $this;
    }

    public function getCodePostal(): ?string
    {
        return $this->codePostal;
    }

    public function setCodePostal(string $codePostal): self
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    public function getVilleNom(): ?string
    {
        return $this->villeNom;
    }

    public function setVilleNom(string $villeNom): self
    {
        $this->villeNom = $villeNom;

        return $this;
    }

    public function getComplement(): ?string
    {
        return $this->complement;
    }

    public function setComplement(?string $complement): self
    {
        $this->complement = $complement;

        return $this;
    }


}
