<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Serializer\Annotation\Groups;


use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("produit")]
    private ?int $id= null;

    #[ORM\Column(length: 255)]
    #[Groups("produit")]
    private ?string $NomProduit = null;

    #[ORM\Column]
    #[Groups("produit")]
    private ?float $PrixProduit = null;

    #[ORM\Column(length: 255)]
    #[Groups("produit")]
    private ?string $QuantiteProduit = null;

    #[ORM\Column(length: 255)]
    #[Groups("produit")]
    private ?string $ImageProduit = null;

    #[ORM\Column(length: 255)]
    #[Groups("produit")]
    private ?string $DescriptionProduit = null;

    #[ORM\ManyToOne(inversedBy: 'Produits')]
    #[Groups("produit")]
    private ?Categorie $categorie = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomProduit(): ?string
    {
        return $this->NomProduit;
    }

    public function setNomProduit(string $NomProduit): self
    {
        $this->NomProduit = $NomProduit;

        return $this;
    }

    public function getPrixProduit(): ?float
    {
        return $this->PrixProduit;
    }

    public function setPrixProduit(float $PrixProduit): self
    {
        $this->PrixProduit = $PrixProduit;

        return $this;
    }

    public function getQuantiteProduit(): ?string
    {
        return $this->QuantiteProduit;
    }

    public function setQuantiteProduit(string $QuantiteProduit): self
    {
        $this->QuantiteProduit = $QuantiteProduit;

        return $this;
    }

    public function getImageProduit(): ?string
    {
        return $this->ImageProduit;
    }

    public function setImageProduit(string $ImageProduit): self
    {
        $this->ImageProduit = $ImageProduit;

        return $this;
    }

    public function getDescriptionProduit(): ?string
    {
        return $this->DescriptionProduit;
    }

    public function setDescriptionProduit(string $DescriptionProduit): self
    {
        $this->DescriptionProduit = $DescriptionProduit;

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
}
