<?php

namespace App\Interfaces;

use App\Entity\Admin;

interface AdminStrategyInterface
{
    public function setStrategy(): self;
    public function createAdmin(): Admin;
    public  function getStrategy(): void;

}