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
    public function getAllAdmins(): array | null
    {
//        file_put_contents('logs.txt', json_encode($this->adminRepository->findAll()));
        return $this->adminRepository->findAll() ?? null;

    }

    /**
     * @param string $id
     * @return Admin|null
     */
    public function findAdminById(string $id) : ?Admin
    {
        return $this->adminRepository->find($id) ?? null;

    }

    /**
     * @param AdminDTO $adminData
     * @return Admin|JsonResponse
     */
    public function createAdmin(AdminDTO $adminData): Admin | JsonResponse
    {
        if($this->validateData($adminData))
        {
            return new JsonResponse([
                'message' => 'Validation was not successful!',
                'errors' => $this->validateData($adminData)
            ]);
        }

        $strategy = new AdminStrategyFactory($adminData);
        $adminCreator = new AdminCreator($this->adminRepository);

        return $adminCreator->setStrategy($strategy->createAdminStrategy())->createAdmin($adminData);
//        return new JsonResponse("działa");
    }

    /**
     * @param AdminDTO $adminDto
     * @param $id
     * @return Admin
     * @throws \Exception
     */
    public function updateAdmin(AdminDTO $adminDto, $id): Admin
    {
        $this->validateData($adminDto) && throw new \Exception();

        $admin = $this->adminRepository->find($id) ?? null;
        if (!$admin) throw new \Exception();
        $admin->setFirstName($adminDto->firstName);
        $admin->setSecondName($adminDto->secondName);
        $admin->setEmail($adminDto->email);

        $this->adminRepository->save($admin);

        return $admin;

    }

    /**
     * @param $id
     * @return Admin
     * @throws \Exception
     */
    public function deleteAdmin($id): Admin
    {
        $admin = $this->adminRepository->find($id) ?? null;
        if (!$admin) throw new \Exception();

        $this->adminRepository->delete($admin);

        return $admin;
    }

    /**
     * @param AdminDTO $data
     * @return array|null
     */
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