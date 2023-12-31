<?php

namespace App\Entity;

use App\Repository\AdminRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: AdminRepository::class)]
class Admin
{

    #[Groups('read')]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\Column(type: 'string', unique: true)]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private $id = null;

    #[Groups('read')]
    #[ORM\Column(length: 255)]
    private ?string $first_name = null;

    #[Groups('read')]
    #[ORM\Column(length: 255)]
    private ?string $second_name = null;

    #[Groups('read')]
    #[ORM\Column(length: 255, unique: false)]
    private ?string $email = null;

    #[Groups('read')]
    #[ORM\Column(length: 255, unique: false)]
    private ?string $employeeCode = null;

    #[Groups('read')]
    #[ORM\OneToMany(mappedBy: 'admin', targetEntity: File::class, cascade: ['persist'])]
    private Collection $files;

    public function __construct()
    {
        $this->files = new ArrayCollection();
    }




    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId($id): static
    {
        $this->id = $id;
        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): static
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getSecondName(): ?string
    {
        return $this->second_name;
    }

    public function setSecondName(string $second_name): static
    {
        $this->second_name = $second_name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getEmployeeCode(): ?string
    {
        return $this->employeeCode;
    }

    public function setEmployeeCode(string $employeeCode): static
    {
        $this->employeeCode = $employeeCode;

        return $this;
    }

    /**
     * @return Collection<int, File>
     */
    public function getFiles(): Collection
    {
        return $this->files;
    }

    public function addFile(File $file): static
    {
        if (!$this->files->contains($file)) {
            $this->files->add($file);
            $file->setAdmin($this);
        }

        return $this;
    }

    public function removeFile(File $file): static
    {
        if ($this->files->removeElement($file)) {
            // set the owning side to null (unless already changed)
            if ($file->getAdmin() === $this) {
                $file->setAdmin(null);
            }
        }

        return $this;
    }


}
