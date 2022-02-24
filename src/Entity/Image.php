<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Image
 *
 * @ORM\Table(name="image")
 * @ORM\Entity
 */
class Image
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_image", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idImage;

    /**
     * @var string|null
     *
     * @ORM\Column(name="lien", type="string", length=250, nullable=true)
     */
    private $lien;

    /**
     * @var \Produit
     *
     * @ORM\ManyToOne(targetEntity="Produit", inversedBy="images")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_produit", referencedColumnName="id", nullable=false)
     * })
     */
    private $idProduit;

    public function getIdImage(): ?int
    {
        return $this->idImage;
    }

    public function __toString()
    {
        return $this->lien;
    }

    public function getLien(): ?string
    {
        return $this->lien;
    }

    public function setLien(?string $lien): self
    {
        $this->lien = $lien;

        return $this;
    }

    public function getIdProduit(): ?Produit
    {
        return $this->idProduit;
    }

    public function setIdProduit(?Produit $idProduit): self
    {
        $this->idProduit = $idProduit;

        return $this;
    }


}
