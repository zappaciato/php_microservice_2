<?php

namespace App\Entity;

use App\Repository\FileRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: FileRepository::class)]
class File
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups('adminDTO')]
    #[ORM\Column(length: 255)]
    private ?string $fileName = null;

    #[Groups('adminDTO')]
    #[ORM\Column(length: 255)]
    private ?string $path = null;


    #[Groups('adminDTO')]
    #[ORM\ManyToOne(inversedBy: 'files')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Admin $relation = null;


//    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
//    private ?\DateTimeInterface $uploadDate = null;
//
    private string $uploadDate = '';

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(Uuid $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): static
    {
        $this->path = $path;

        return $this;
    }

    public function getRelation(): ?Admin
    {
        return $this->relation;
    }

    public function setRelation(?Admin $relation): static
    {
        $this->relation = $relation;

        return $this;
    }

    public function getUploadDate(): ?\DateTimeInterface
    {
        return $this->uploadDate;
    }

    public function setUploadDate(string $uploadDate): static
    {
        $this->uploadDate = $uploadDate;

        return $this;
    }
}