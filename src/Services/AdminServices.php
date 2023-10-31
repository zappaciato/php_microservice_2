<?php

namespace App\Services;

use App\DTO\AdminDTO;
use App\Entity\Admin;
use App\Repository\AdminRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;


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

    public function findAdminById(string $id) : ?Admin
    {
        return $this->adminRepository->find($id) ?? null;

    }

    public function createAdmin(AdminDTO $adminData): Admin | array
    {
        if($this->validateData($adminData))
        {
            return $this->validateData($adminData);
        }


        $strategy = new AdminStrategyFactory($adminData);
        $strategy = $strategy->createAdminStrategy();
        $adminCreator = new AdminCreator($this->adminRepository);
        $adminCreator->setStrategy($strategy);

        return    $adminCreator->createAdmin($adminData);
    }

    /**
     * @throws \Exception
     */
    public function updateAdmin(AdminDTO $adminDto, $id): Admin
    {
        $this->validateData($adminDto) && throw new \Exception();

        $admin = $this->adminRepository->find($id) ?? null;
        echo "I jave found one!";
        if (!$admin) throw new \Exception();
        $admin->setFirstName($adminDto->firstName);
        $admin->setSecondName($adminDto->secondName);
        $admin->setEmail($adminDto->email);


        $this->adminRepository->save($admin);

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