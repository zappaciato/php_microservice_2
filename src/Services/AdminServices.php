<?php

namespace App\Services;

use App\DTO\AdminDTO;
use App\Entity\Admin;
use App\Repository\AdminRepository;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Cassandra\Uuid;
use PhpParser\Node\Expr\Array_;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminServices
{
    private AdminRepository $adminRepository;
    private ValidatorInterface $validator;

    public function __construct(AdminRepository $adminRepository, ValidatorInterface $validator)
    {
        $this->adminRepository = $adminRepository;

        $this->validator = $validator;
    }

    /**
     * @return array<Admin|null>
     */
    public function getAllAdmins(): array
    {
        return $this->adminRepository->findAll();

    }

    public function createAdmin(AdminDTO $adminData): Admin | array
    {
        if($this->validateData($adminData))
        {
            return $this->validateData($adminData);
        }

//        $user = new UserCreationStrategyFactory($userDto, $this->userCreator);
//        $this->userCreator->setStrategy($user->createUserStrategy()); //tutaj może by ddalo rade zrobic trzy poziomy adminów.. supoeradmin, level gold, silver, bronze;
//
//
//        $user = $this->userCreator->create($userDto, $this->userRepository);

        $admin = new Admin();
        print_r($admin->getId());
        $admin->setId($admin->getId());
        $admin->setFirstName($adminData->firstName);
        $admin->setSecondName($adminData->secondName);
        $admin->setEmail($adminData->email);
        $admin->setEmployeeCode($adminData->employeeCode);
        print_r($admin);
        $this->adminRepository->save($admin);
        print_r(" ".$admin->getId());
        echo "III've gone thaaat faar!";
        print_r($admin);
        return $admin;
    }

    private function validateData(AdminDTO $data): array|null
    {

        $errors = $this->validator->validate($data);

        if (count($errors) > 0) {

            $errorsString = [];
            foreach ($errors as $error) {
                $propertyPath = $error->getPropertyPath();
                $message = $error->getMessage();
                $errorsString[] = "$propertyPath: $message";
            }

            return $errorsString;
        }

        return null;
    }
}