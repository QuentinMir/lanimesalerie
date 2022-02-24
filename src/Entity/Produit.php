<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Produit
 *
 * @ORM\Table(name="produit", indexes={@ORM\Index(name="fk__id_souscategorie", columns={"id_sous_categorie"}), @ORM\Index(name="fk__id_marque", columns={"id_marque"}), @ORM\Index(name="fk__id_categorie", columns={"id_categorie"}), @ORM\Index(name="fk__id_subsouscategorie", columns={"id_sub_sous_categorie"})})
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
     *
     * @ORM\Column(name="nom", type="string", length=50, nullable=false)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", length=65535, nullable=false)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="prix_ht", type="decimal", precision=15, scale=2, nullable=false)
     */
    private $prixHt;

    /**
     * @var \Subsouscategorie
     *
     * @ORM\ManyToOne(targetEntity="Subsouscategorie", inversedBy="produits")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_sub_sous_categorie", referencedColumnName="id_sub_sous_categorie", nullable=true)
     * })
     */
    private $idSubSousCategorie;

    /**
     * @var \Categorie
     *
     * @ORM\ManyToOne(targetEntity="Categorie", inversedBy="produits")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_categorie", referencedColumnName="id_categorie", nullable=false)
     * })
     */
    private $idCategorie;

    /**
     * @var \Souscategorie
     *
     * @ORM\ManyToOne(targetEntity="Souscategorie", inversedBy="produits")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_sous_categorie", referencedColumnName="id_sous_categorie", nullable=false)
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
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Commande", inversedBy="idProduit")
     * @ORM\JoinTable(name="contenir",
     *   joinColumns={
     *     @ORM\JoinColumn(name="id_produit", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="id_commande", referencedColumnName="id_commande")
     *   }
     * )
     */
    private $idCommande;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Image", mappedBy="idProduit")
     */
    private $images;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->idCommande = new \Doctrine\Common\Collections\ArrayCollection();
        $this->images = new \Doctrine\Common\Collections\ArrayCollection();
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

    /**
     * @return Collection|Commande[]
     */
    public function getIdCommande(): Collection
    {
        return $this->idCommande;
    }

    public function addIdCommande(Commande $idCommande): self
    {
        if (!$this->idCommande->contains($idCommande)) {
            $this->idCommande[] = $idCommande;
        }

        return $this;
    }

    public function removeIdCommande(Commande $idCommande): self
    {
        $this->idCommande->removeElement($idCommande);

        return $this;
    }

}
