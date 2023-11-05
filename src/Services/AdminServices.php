<?php

namespace App\Services;

use App\DTO\AdminDTO;
use App\Entity\Admin;
use App\Entity\File;
use App\Repository\AdminRepository;
use App\Repository\FileRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class AdminServices
{
    private AdminRepository $adminRepository;
    private ValidatorInterface $validator;
    private FileService $fileService;
    private FileRepository $fileRepository;

    public function __construct(AdminRepository $adminRepository, ValidatorInterface $validator, FileRepository $fileRepository)
    {
        $this->adminRepository = $adminRepository;
        $this->validator = $validator;
        $this->fileRepository = $fileRepository;
    }

    /**
     * @return array<Admin|null>
     */
    public function getAllAdmins(): array | null
    {

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
    public function createAdmin(AdminDTO $adminData, UploadedFile $file = null): Admin | JsonResponse
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
        $admin = $adminCreator->setStrategy($strategy->createAdminStrategy())->createAdmin($adminData);

        if($file !== null) {
            $this->saveAdminFile($file, $admin);
        }

        return $admin;
    }

    private function saveAdminFile(UploadedFile $file, Admin $admin) : void
    {
        $newFile = new FileService($file, $admin);
        $preparedFile = $newFile->prepareFile();
        $this->fileRepository->save($preparedFile);
//            $admin->addFile($preparedFile); // tym sposobem mam circular reference
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