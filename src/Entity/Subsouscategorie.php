<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Subsouscategorie
 *
 * @ORM\Table(name="subsouscategorie")
 * @ORM\Entity
 */
class Subsouscategorie
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_sub_sous_categorie", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idSubSousCategorie;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=50, nullable=false)
     */
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Produit", mappedBy="idSubSousCategorie")
     * @ORM\JoinColumn(nullable=false)
     */
    private $produits;

    public function __construct()
    {
        $this->produits = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function addProduit(Produit $produit)
    {
        if (!$this->produits->contains($produit)) {
            $this->produits->add($produit);
        }
    }

    public function removeProduit(Produit $produit)
    {
        if ($this->produits->contains($produit)) {
            $this->produits->removeElement($produit);
        }
    }


    public function getIdSubSousCategorie(): ?int
    {
        return $this->idSubSousCategorie;
    }

    public function __toString()
    {
        return $this->nom;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getProduits(): Collection
    {
        return $this->produits;
    }


}
