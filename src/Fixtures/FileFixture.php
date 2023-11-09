<?php
//
//namespace App\Fixtures;
//
//use App\Entity\File;
//use App\Providers\FileProvider;
//use Doctrine\Bundle\FixturesBundle\Fixture;
//use Doctrine\Persistence\ObjectManager;
//use Faker\Factory;
//
//class FileFixture extends Fixture
//{
//
//    /**
//     * @inheritDoc
//     */
//    public function load(ObjectManager $manager)
//    {
//        $faker = Factory::create();
//        $faker->addProvider(new FileProvider($faker));
//
//        for ($i = 0; $i < 100; $i++) {
//            $file = new File();
//            $file->setFileName($faker->randomFileName('.txt'));
//            $file->setPath('File/');
//            $file->setAdmin()
//
//
//
//            $manager->persist($file);
//        }
//
//        $manager->flush();
//    }
//    }
//}