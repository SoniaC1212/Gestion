<?php

namespace App\Entity;

use App\Repository\MatiereRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MatiereRepository::class)]
class Matiere
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"libelle is required")]
    private ?string $libelle = null;

    

    #[ORM\ManyToMany(targetEntity: Cours::class, inversedBy: 'matiere',cascade: ['persist'])]
    #[Assert\NotBlank(message:"cours is required")]
    private Collection $cours;


    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"image is required")]
    private ?string $image = null;

    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    
    

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection<int, Cours>
     */
    
    public function __construct()
    {
        $this->cours = new ArrayCollection();
    }

    public function getCours(): Collection
    {
        return $this->cours;
    }

    public function addCours(Cours $cours): self
    {
        if (!$this->cours->contains($cours)) {
            $this->cours->add($cours);
        }

        return $this;
    }

    public function removeCours(Cours $cours): self
    {
        $this->cours->removeElement($cours);

        return $this;
    }
}
