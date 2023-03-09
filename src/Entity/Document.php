<?php

namespace App\Entity;

use App\Repository\DocumentRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DocumentRepository::class)]
class Document
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("documents")]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups("documents")]
    #[Assert\NotBlank(message:"nomdocument is required")]

    private ?string $nomdocument = null;

    #[ORM\Column(length: 255)]
    #[Groups("documents")]
    #[Assert\NotBlank(message:"type is required")]

    private ?string $type = null;

    #[ORM\Column(length: 255)]
    #[Groups("documents")]
    #[Assert\NotBlank(message:"editeur is required")]


    private ?string $editeur = null;


    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"image is required")]
    #[Groups("documents")]
    private ?string $image;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"url is required")]
    #[Groups("documents")]
    private ?string $url;




    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomdocument(): ?string
    {
        return $this->nomdocument;
    }

    public function setNomdocument(string $nomdocument): self
    {
        $this->nomdocument = $nomdocument;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getEditeur(): ?string
    {
        return $this->editeur;
    }

    public function setEditeur(string $editeur): self
    {
        $this->editeur = $editeur;

        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }
    public function geturl()
    {
        return $this->url;
    }

    public function seturl($url)
    {
        $this->url = $url;

        return $this;
    }
/*
    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }
*/
}
