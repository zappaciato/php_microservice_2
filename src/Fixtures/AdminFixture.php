<?php

namespace App\Fixtures;


use App\Entity\Admin;
use App\Entity\File;
use App\Providers\EmployeeCodeCustomProvider;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use phpDocumentor\Reflection\Types\InterfaceString;

class AdminFixture extends Fixture
{

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        $this->generateEmployeeCode();
        for ($i = 0; $i < 100; $i++) {
            $admin = new Admin();
            $admin->setFirstName($faker->firstName);
            $admin->setSecondName($faker->lastName);
            $admin->setEmail($faker->email);
            $admin->setEmployeeCode($this->generateEmployeeCode());

            $admin->addFile($this->generateFile($faker, $admin, $manager));

            $manager->persist($admin);
        }

        $manager->flush();
    }

    private function generateFile(Generator $faker, Admin $admin, ObjectManager $manager) : File
    {
        $content = 'This is for Admin: ' .$admin->getEmail();

        $file = new File();
        $file->setFileName($faker->word.".txt");
        $file->setPath('\Files\\');
        $file->setAdmin($admin);

        $manager->persist($file);

        file_put_contents($file->getPath().$file->getFileName(), $content);

        return $file;
    }

    private function generateEmployeeCode(): string
    {
        $letters = range('A', 'Z');
        $numbers = range(0, 9);

        return $letters[array_rand($letters)]
            .$letters[array_rand($letters)]
            .$numbers[array_rand($numbers)]
            .$numbers[array_rand($numbers)]
            .$letters[array_rand($letters)]
            .$letters[array_rand($letters)];

    }

}