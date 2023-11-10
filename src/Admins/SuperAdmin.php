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
//        echo ":::::::::::::::::::::::::::::::::::::::::::I AM IN:::::::::::::::::::::::::::::::::::::::";
//        var_dump($adminData->files['fileName']);
//        if (!empty($adminData->files)) {
//
//
//                $uploadedFile = new File();
//                $uploadedFile
//                    ->setFileName($adminData->files['fileName'])
//                    ->setPath($adminData->files['path'])
//                    ->setRelation($admin)
//                    ->setUploadDate('2022-03-22');
//
//
//                $admin->addFile($uploadedFile);
//
//            }

        return $admin;
    }


}