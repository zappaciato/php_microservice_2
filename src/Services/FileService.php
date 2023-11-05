<?php

namespace App\Services;


use App\Entity\Admin;
use App\Entity\File;
use App\Repository\FileRepository;

use Symfony\Component\HttpFoundation\File\UploadedFile;


class FileService
{
    public array $fileData;
    public function __construct ( private readonly UploadedFile $file, private readonly Admin $admin)
    {

    }

    private function establishFileParameters() : self
    {
        $this->fileData = [
            'fileName' => uniqid().$this->file->getClientOriginalName(),
            'path' => 'Files/'
        ];

        return $this;
    }

    private function setFileParameters() : File
    {
        $newFile = new File();
        $newFile->setFileName($this->fileData['fileName']);
        $newFile->setPath($this->fileData['path']);
        $newFile->setAdmin($this->admin);

        return $newFile;
    }

    private function moveToFolder() : self
    {
        $this->file->move($this->fileData['path'], $this->fileData['fileName']);

        return $this;
    }

    public function prepareFile() : File
    {
        return $this->establishFileParameters()->moveToFolder()->setFileParameters();

    }




}
