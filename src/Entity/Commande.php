<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Commande
 *
 * @ORM\Table(name="commande", indexes={@ORM\Index(name="fk__id_poyen_paiement", columns={"id_moyen_paiement"}), @ORM\Index(name="fk__id_etat", columns={"Id_etat"}), @ORM\Index(name="fk__id_user", columns={"id_user"})})
 * @ORM\Entity
 */
class Commande
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_commande", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idCommande;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="date_commande", type="date", nullable=true)
     */
    private $dateCommande;

    /**
     * @var string|null
     *
     * @ORM\Column(name="numero", type="string", length=20, nullable=true)
     */
    private $numero;

    /**
     * @var \Etat
     *
     * @ORM\ManyToOne(targetEntity="Etat")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Id_etat", referencedColumnName="id_etat")
     * })
     */
    private $idEtat;

    /**
     * @var \Utilisateur
     *
     * @ORM\ManyToOne(targetEntity="Utilisateur")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_user", referencedColumnName="id_user")
     * })
     */
    private $idUser;

    /**
     * @var \Moyenpaiement
     *
     * @ORM\ManyToOne(targetEntity="Moyenpaiement")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_moyen_paiement", referencedColumnName="id_moyen_paiement")
     * })
     */
    private $idMoyenPaiement;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Produit", mappedBy="idCommande")
     */
    private $idProduit;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->idProduit = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getIdCommande(): ?int
    {
        return $this->idCommande;
    }

    public function getDateCommande(): ?\DateTimeInterface
    {
        return $this->dateCommande;
    }

    public function setDateCommande(?\DateTimeInterface $dateCommande): self
    {
        $this->dateCommande = $dateCommande;

        return $this;
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(?string $numero): self
    {
        $this->numero = $numero;

        return $this;
    }

    public function getIdEtat(): ?Etat
    {
        return $this->idEtat;
    }

    public function setIdEtat(?Etat $idEtat): self
    {
        $this->idEtat = $idEtat;

        return $this;
    }

    public function getIdUser(): ?Utilisateur
    {
        return $this->idUser;
    }

    public function setIdUser(?Utilisateur $idUser): self
    {
        $this->idUser = $idUser;

        return $this;
    }

    public function getIdMoyenPaiement(): ?Moyenpaiement
    {
        return $this->idMoyenPaiement;
    }

    public function setIdMoyenPaiement(?Moyenpaiement $idMoyenPaiement): self
    {
        $this->idMoyenPaiement = $idMoyenPaiement;

        return $this;
    }

    /**
     * @return Collection|Produit[]
     */
    public function getIdProduit(): Collection
    {
        return $this->idProduit;
    }

    public function addIdProduit(Produit $idProduit): self
    {
        if (!$this->idProduit->contains($idProduit)) {
            $this->idProduit[] = $idProduit;
            $idProduit->addIdCommande($this);
        }

        return $this;
    }

    public function removeIdProduit(Produit $idProduit): self
    {
        if ($this->idProduit->removeElement($idProduit)) {
            $idProduit->removeIdCommande($this);
        }

        return $this;
    }

}
