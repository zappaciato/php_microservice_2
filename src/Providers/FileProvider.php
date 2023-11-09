<?php

namespace App\Providers;

use Faker\Provider\Base;

class FileProvider extends Base
{
    public function randomFileName($extension = '.txt'): string
    {
        $fileName = $this->generator->word;

        return $fileName.$extension;

    }

}