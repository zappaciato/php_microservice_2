<?php

namespace App\Services;

use App\DTO\AdminDTO;
use App\Entity\Admin;
use App\Entity\File;
use App\Exceptions\AdminValidationException;
use App\Repository\AdminRepository;
use App\Repository\FileRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class AdminServices
{
    private AdminRepository $adminRepository;
    private ValidatorInterface $validator;
    private FileRepository $fileRepository;

    public function __construct(AdminRepository $adminRepository, ValidatorInterface $validator, FileRepository $fileRepository)
    {
        $this->adminRepository = $adminRepository;
        $this->validator = $validator;
        $this->fileRepository = $fileRepository;
    }

    /**
     * @return array|null
     */
    public function getAllAdmins(): array | null
    {
           return $this->adminRepository->findAll() ?? null;
    }

    /**
     * @return array|null
     */
    public function getAllFiles(): array | null
    {
        return $this->fileRepository->findAll() ?? null;
    }

    public function getAdminFiles(Admin $admin): array | null
    {
        return $this->fileRepository->findBy(['admin' => $admin]) ?? null;

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
     * @param UploadedFile|null $file
     * @return Admin|JsonResponse
     * @throws AdminValidationException
     */
    public function createAdmin(AdminDTO $adminData, UploadedFile $file = null): Admin | JsonResponse
    {
        $this->validateData($adminData) && throw new AdminValidationException();

        $strategy = new AdminStrategyFactory($adminData);
        $adminCreator = new AdminCreator($this->adminRepository);
        $admin = $adminCreator->setStrategy($strategy->createAdminStrategy())->createAdmin($adminData);

        if($file !== null) {
            $this->saveAdminFile($file, $admin);
        }

        return $admin;
    }

    /**
     * @param UploadedFile $file
     * @param Admin $admin
     * @return Admin
     */
    public function saveAdminFile(UploadedFile $file, Admin $admin): Admin
    {
        $newFile = new FileService($file, $admin);
        $preparedFile = $newFile->prepareFile();

        $admin->addFile($preparedFile);
        $this->adminRepository->save($admin);

        return $admin;
    }

    /**
     * @param AdminDTO $adminData
     * @param $id
     * @return JsonResponse|Admin
     * @throws AdminValidationException
     */
    public function updateAdmin(AdminDTO $adminData, $id): JsonResponse | Admin
    {
        $this->validateData($adminData) && throw new AdminValidationException();

        $admin = $this->adminRepository->find($id) ?? null;
        if (!$admin) throw new \Exception();

        $this->setAllowedUpdateFields($admin, $adminData);
        $this->adminRepository->save($admin);

        return $admin;

    }

    /**
     * @param Admin $admin
     * @param AdminDTO $adminDto
     * @return void
     */
    private function setAllowedUpdateFields(Admin $admin, AdminDTO $adminDto): void
    {
        $admin->setFirstName($adminDto->firstName);
        $admin->setSecondName($adminDto->secondName);
        $admin->setEmail($adminDto->email);
        $admin->setEmployeeCode($adminDto->employeeCode);
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

    public function removeFile($adminId, $fileId) : Admin | JsonResponse
    {
        $admin = $this->adminRepository->find($adminId) ?? null;
        $files = $admin->getFiles();

        foreach($files as $file) {
            if($file->getId() === $fileId) {
                print_r($file->getFileName());
                $admin->removeFile($file);
                $this->fileRepository->delete($file);

                return new JsonResponse(['message' => 'The file '.$file->getFileName().' has been successfully removed!']);
            }
        }

        return new JsonResponse(['message' => 'All files for the admin '.$admin->getFirstName(). ' ' .$admin->getSecondName().' have been removed!']);
    }

    /**
     * @param AdminDTO $data
     * @return array|null
     */
    private function validateData(AdminDTO $data): array|null
    {
        $errors = $this->validator->validate($data);
        $errorsArray = [];

        if (count($errors) > 0) {
            foreach ($errors as $error) {
                $propertyPath = $error->getPropertyPath();
                $message = $error->getMessage();
                $errorsArray[$propertyPath][] = "$propertyPath: $message";
            }

            return $errorsArray;
        }

        return null;
    }
}