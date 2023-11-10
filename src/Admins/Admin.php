<?php

namespace App\Admins;

use App\DTO\AdminDTO;
use App\Entity\File;
use App\interfaces\AdminCreatorStrategyInterface;

class Admin implements AdminCreatorStrategyInterface
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

        if (isset($adminData->files)) {

            foreach ($adminData->files as $file) {
                $file = new File();
                $file
                    ->setFileName($file['fileName'])
                    ->setPath($file['path'])
                    ->setRelation($admin)
                    ->setUploadDate('2022-03-22');

    echo "jestem w ADMIN normlany create last";
                $admin->addFile($file);


            }

        }
        return $admin;
    }
}
