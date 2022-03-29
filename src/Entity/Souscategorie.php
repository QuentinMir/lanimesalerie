<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Souscategorie
 *
 * @ORM\Table(name="souscategorie")
 * @ORM\Entity
 */
class Souscategorie
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=50, nullable=false)
     */
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Produit", mappedBy="idSousCategorie", orphanRemoval=true)
     * @ORM\JoinColumn(nullable=false)
     */
    private $produits;

    /**
     * @ORM\Column(type="string", length=250, nullable=true)
     */
    private $imageLink;

    /**
     * @ORM\ManyToOne(targetEntity=Categorie::class, inversedBy="souscategories")
     * @ORM\JoinColumn(nullable=false)
     */
    private $categorie;

    /**
     * @ORM\OneToMany(targetEntity=Subsouscategorie::class, mappedBy="souscategorie", orphanRemoval=true)
     */
    private $subsouscategories;

    public function __construct()
    {
        $this->produits = new \Doctrine\Common\Collections\ArrayCollection();
        $this->subsouscategories = new ArrayCollection();
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


    public function getId(): ?int
    {
        return $this->id;
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

    public function getImageLink(): ?string
    {
        return $this->imageLink;
    }

    public function setImageLink(?string $imageLink): self
    {
        $this->imageLink = $imageLink;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * @return Collection|Subsouscategorie[]
     */
    public function getSubsouscategories(): Collection
    {
        return $this->subsouscategories;
    }

    public function addSubsouscategory(Subsouscategorie $subsouscategory): self
    {
        if (!$this->subsouscategories->contains($subsouscategory)) {
            $this->subsouscategories[] = $subsouscategory;
            $subsouscategory->setSouscategorie($this);
        }

        return $this;
    }

    public function removeSubsouscategory(Subsouscategorie $subsouscategory): self
    {
        if ($this->subsouscategories->removeElement($subsouscategory)) {
            // set the owning side to null (unless already changed)
            if ($subsouscategory->getSouscategorie() === $this) {
                $subsouscategory->setSouscategorie(null);
            }
        }

        return $this;
    }

}
