<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Produit
 *
 * @ORM\Table(name="produit")
 * @ORM\Entity
 */
class Produit
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
     * @Assert\NotBlank
     * @ORM\Column(name="nom", type="string", length=50, nullable=false)
     */
    private $nom;

    /**
     * @var string
     * @Assert\NotBlank
     * @ORM\Column(name="description", type="text", length=65535, nullable=false)
     */
    private $description;

    /**
     * @var string
     * @Assert\NotBlank
     * @ORM\Column(name="prix_ht", type="decimal", precision=15, scale=2, nullable=false)
     */
    private $prixHt;

    /**
     * @var \Subsouscategorie
     * @Assert\NotBlank
     * @ORM\ManyToOne(targetEntity="Subsouscategorie", inversedBy="produits")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_sub_sous_categorie", referencedColumnName="id", nullable=true)
     * })
     */
    private $idSubSousCategorie;

    /**
     * @var \Categorie
     * @Assert\NotBlank
     * @ORM\ManyToOne(targetEntity="Categorie", inversedBy="produits")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_categorie", referencedColumnName="id", nullable=false)
     * })
     */
    private $idCategorie;

    /**
     * @var \Souscategorie
     * @Assert\NotBlank
     * @ORM\ManyToOne(targetEntity="Souscategorie", inversedBy="produits")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_sous_categorie", referencedColumnName="id", nullable=false)
     * })
     */
    private $idSousCategorie;

    /**
     * @var \Marque
     *
     * @ORM\ManyToOne(targetEntity="Marque", inversedBy="produits")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_marque", referencedColumnName="id_marque", nullable=false)
     * })
     */
    private $idMarque;


    /**
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Image", mappedBy="idProduit", orphanRemoval=true, cascade={"persist"})
     */
    private $images;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $estDispo;

    /**
     * @ORM\OneToMany(targetEntity=Avis::class, mappedBy="produit", orphanRemoval=true)
     */
    private $avis;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->idCommande = new \Doctrine\Common\Collections\ArrayCollection();
        $this->images = new \Doctrine\Common\Collections\ArrayCollection();
        $this->avis = new ArrayCollection();
    }


    public function addImage(Image $image)
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
        }
    }

    public function removeImage(Image $image)
    {
        if ($this->images->contains($image)) {
            $this->images->removeElement($image);
        }
    }

    public function __toString()
    {
        return $this->nom;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrixHt(): ?string
    {
        return $this->prixHt;
    }

    public function setPrixHt(string $prixHt): self
    {
        $this->prixHt = $prixHt;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function getIdSubSousCategorie(): ?Subsouscategorie
    {
        return $this->idSubSousCategorie;
    }

    public function setIdSubSousCategorie(?Subsouscategorie $idSubSousCategorie): self
    {
        $this->idSubSousCategorie = $idSubSousCategorie;

        return $this;
    }

    public function getIdCategorie(): ?Categorie
    {
        return $this->idCategorie;
    }

    public function setIdCategorie(?Categorie $idCategorie): self
    {
        $this->idCategorie = $idCategorie;

        return $this;
    }

    public function getIdSousCategorie(): ?Souscategorie
    {
        return $this->idSousCategorie;
    }

    public function setIdSousCategorie(?Souscategorie $idSousCategorie): self
    {
        $this->idSousCategorie = $idSousCategorie;

        return $this;
    }

    public function getIdMarque(): ?Marque
    {
        return $this->idMarque;
    }

    public function setIdMarque(?Marque $idMarque): self
    {
        $this->idMarque = $idMarque;

        return $this;
    }


    public function getEstDispo(): ?bool
    {
        return $this->estDispo;
    }

    public function setEstDispo(?bool $estDispo): self
    {
        $this->estDispo = $estDispo;

        return $this;
    }

    /**
     * @return Collection|Avis[]
     */
    public function getAvis(): Collection
    {
        return $this->avis;
    }

    public function addAvi(Avis $avi): self
    {
        if (!$this->avis->contains($avi)) {
            $this->avis[] = $avi;
            $avi->setProduit($this);
        }

        return $this;
    }

    public function removeAvi(Avis $avi): self
    {
        if ($this->avis->removeElement($avi)) {
            // set the owning side to null (unless already changed)
            if ($avi->getProduit() === $this) {
                $avi->setProduit(null);
            }
        }

        return $this;
    }

}
