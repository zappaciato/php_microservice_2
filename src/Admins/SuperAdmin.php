<?php

namespace App\Admins;

use App\DTO\AdminDTO;
use App\Entity\File;
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

        if (isset($adminData->files)) {


//            foreach ($adminData->files as $file) {

                $uploadedFile = new File();
                $uploadedFile
                    ->setName($adminData->files['fileName'])
                    ->setPath($adminData->files['path'])
                    ->setUploadDate('2022-03-22');


                $admin->addFile($uploadedFile);

            }
        echo "Jestem w adminDataFIle";
//        }
        return $admin;
    }


}