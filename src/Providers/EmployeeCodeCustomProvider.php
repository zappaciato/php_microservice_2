<?php

namespace App\Providers;

use Faker\Provider\Base;

class EmployeeCodeCustomProvider extends Base
{
    public function customEmployeeCodeFormat()
    {
        return $this->regexify('/^[A-Z]{2}\d{2}[A-Z]{2}$/');
    }

}