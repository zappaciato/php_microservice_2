<?php

namespace App\DTO;

use App\Entity\Admin;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\Date;

class FileDTO
{

    #[Groups('adminDTO')]
    private ?string $fileName = null;
    #[Groups('adminDTO')]
    private ?string $uploadDate = '23.04.2023';

    #[Groups('adminDTO')]
    private ?string $path = null;

    // #[Assert\NotNull]
    // #[NotBlank]
    public ?Admin $relation;

}