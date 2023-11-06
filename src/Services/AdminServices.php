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
//        var_dump($this->adminRepository->findAll()[0]->getEmail());

        return $this->adminRepository->findAll() ?? null;
    }

    public function getAllFiles(): array | null
    {
        return $this->fileRepository->findAll() ?? null;

    }

    public function getAdminFiles(Admin $admin): array | null
    {
        return $this->fileRepository->findBy(['admin' => $admin]) ?? null; //również circular reference

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
     * @throws \Exception
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

    /**
     * @param UploadedFile $file
     * @param Admin $admin
     * @return void
     */
    public function saveAdminFile(UploadedFile $file, Admin $admin) : void
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
    public function updateAdmin(AdminDTO $adminDto, $id): Admin | JsonResponse
    {
        if($this->validateData($adminDto))  return new JsonResponse(['message' => $this->validateData($adminDto)]);
        $admin = $this->adminRepository->find($id) ?? null;
        if (!$admin) throw new \Exception();
        $this->setAllowedUpdateFields($admin, $adminDto);
        $this->adminRepository->save($admin);

        return $admin;

    }

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