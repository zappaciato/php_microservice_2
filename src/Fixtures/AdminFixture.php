<?php

namespace App\Fixtures;


use App\Entity\Admin;
use App\Entity\File;
use App\Providers\EmployeeCodeCustomProvider;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class AdminFixture extends Fixture
{
    //nie moge konstruktora bo metoda load musi miec argument, czy tak jest dobrze?

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
//        $employeeCode = new EmployeeCodeCustomProvider($faker);
//        $faker->addProvider($employeeCode);
//        $faker->addProvider(provider: new EmployeeCodeCustomProvider($faker));
        for ($i = 0; $i < 100; $i++) {
            $admin = new Admin();
            $admin->setFirstName($faker->firstName);
            $admin->setSecondName($faker->lastName);
            $admin->setEmail($faker->email);
//            $admin->setEmployeeCode($faker->customEmployeeCodeFormat());
            $admin->setEmployeeCode('AA11BB');

            $admin->addFile($this->generateFile($faker, $admin, $manager));

            $manager->persist($admin);
        }

        $manager->flush();
    }

    private function generateFile(Generator $faker, Admin $admin, ObjectManager $manager) : File
    {
//        $faker = Factory::create();
        $content = 'This is for Admin: ' .$admin->getEmail();

        $file = new File();
        $file->setFileName($faker->word.".txt");
        $file->setPath('Public\Files\\');
        $file->setAdmin($admin);

        $manager->persist($file);



        file_put_contents($file->getPath().$file->getFileName(), $content);

        return $file;
    }

}