<?php

namespace App\DTO;

use App\Entity\Admin;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\Date;

class FileDTO
{

    #[Groups('fileDTO')]
    private ?string $fileName = null;
    #[Groups('fileDTO')]
    private ?string $uploadDate = '';

    #[Groups('fileDTO')]
    private ?string $path = null;

    // #[Assert\NotNull]
    // #[NotBlank]
    public ?Admin $relation;

}