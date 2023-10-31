<?php

namespace App\interfaces;

use App\DTO\AdminDTO;
use App\Entity\Admin;

interface AdminCreatorStrategyInterface
{
    public function createAdmin(AdminDTO $adminData): Admin;
}