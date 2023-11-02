<?php

namespace App\Admins;

use App\DTO\AdminDTO;
use App\Interfaces\AdminCreatorStrategyInterface;

class SuperAdmin implements AdminCreatorStrategyInterface
{
    /**
     * @param AdminDTO $adminData
     * @return \App\Entity\Admin
     */
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