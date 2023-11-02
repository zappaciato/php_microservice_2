<?php

namespace App\Admins;

use App\DTO\AdminDTO;
use App\interfaces\AdminCreatorStrategyInterface;

class Owner implements AdminCreatorStrategyInterface
{

    public function createAdmin(AdminDTO $adminData): \App\Entity\Admin
    {
        $admin = new \App\Entity\Admin();
        $admin->setFirstName($adminData->firstName);
        $admin->setSecondName($adminData->secondName);
        $admin->setEmail($adminData->email);
        $admin->setEmployeeCode($adminData->employeeCode);

        return $admin;
    }

}