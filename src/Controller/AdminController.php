<?php

namespace App\Controller;

use App\DTO\AdminDTO;
use App\ReusableData\CustomGroups;
use App\Services\AdminServices;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;


class AdminController extends AbstractController
{
    public function __construct(private readonly AdminServices $adminServices)
    {

    }

    /**
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    #[Route('/admins', name: 'app_admins', methods: ['GET'])]
    public function index(SerializerInterface $serializer): JsonResponse
    {
        $admins = $this->adminServices->getAllAdmins();

        if(count($admins) === 0) {
            return new JsonResponse(['message' => 'No users found'], 404); //no content status/ dobrze to zrobic HTTP::
        }

        return $this->json($admins, 200, [], CustomGroups::$contextRead);
    }


    /**
     * @param string $id
     * @return JsonResponse
     */
    #[Route('/admins/{id}/files', name: 'admin_files', methods: ['GET'])]
    public function getAdminFiles(string $id): JsonResponse
    {
        $admin = $this->adminServices->findAdminById($id);
        $files = $this->adminServices->getAdminFiles($admin);

        if(count($files) === 0) {
            return new JsonResponse(['message' => 'Files not found', 'files' => $files], 404);
        }

        return $this->json($files, 200, ['message' => 'Success!'], CustomGroups::$contextRead);
    }

    /**
     * @return JsonResponse
     */
    #[Route('/admins/files', name: 'all_files', methods: ['GET'])]
    public function getAllFiles(): JsonResponse
    {
        $files = $this->adminServices->getAllFiles();
        $count = count($files);

        if(count($files) === 0) {
            return new JsonResponse(['message' => 'Files not found'], 404);
        }

        return $this->json($files, 200, ['message' => 'Success!', 'count' => $count], CustomGroups::$contextRead);
    }

    /**
     * @param string $id
     * @return JsonResponse
     */
    #[Route('/admins/{id}', name: 'app_admin', methods: ['GET'])]
    public function getAdminById(string $id): JsonResponse
    {
        $admin = $this->adminServices->findAdminById($id);
        if(empty($admin)) {

            return new JsonResponse(['message' => 'Admin not found', 'admin' => $admin], 404);
        }

        return $this->json($admin, 200, ['message' => 'Admin found!!!'], CustomGroups::$contextRead);
    }

    /**
     * @param Request $request
     * @param SerializerInterface $serializer
     * @return JsonResponse
     * @throws Exception
     */
    #[Route('/admins ', name: 'create_admin', methods: ['POST'])]
    public function createAdmin(Request $request, SerializerInterface $serializer): JsonResponse
    {
        $requestData = $request->request->all();
        $file =  $request->files->get('file');
        $adminData = $serializer->denormalize($requestData, AdminDTO::class, "array");

        try {
            $admin = $this->adminServices->createAdmin($adminData, $file);
        }
        catch (Exception $e) {
            return $this->json($e->getMessage());
        }

        return $this->json($admin, 200, ['message' => 'Admin created!'], CustomGroups::$contextRead);
    }

    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    #[Route('/admins/{id}/files ', name: 'admin_file', methods: ['POST'])]
    public function addFile(Request $request, $id): JsonResponse
    {
        $file =  $request->files->get('file');
        $admin = $this->adminServices->findAdminById($id);
        $admin = $this->adminServices->saveAdminFile($file, $admin);

        return $this->json($admin, 200, ['message' => 'New Admin added!'], CustomGroups::$contextRead);

    }

    /**
     * @param $adminId
     * @param $fileId
     * @return JsonResponse
     */
    #[Route('/admins/{adminId}/files/{fileId}/delete ', name: 'delete_file', methods: ['DELETE'])]
    public function removeFile($adminId, $fileId): JsonResponse
    {
        return $this->adminServices->removeFile($adminId, $fileId);

    }

    /**
     * @param $id
     * @param Request $request
     * @param SerializerInterface $serializer
     * @return JsonResponse
     * @throws Exception
     *
     */
    #[Route('/admins/{id} ', name: 'update_admin', methods: ['PUT'])]
    public function updateAdmin($id, Request $request, SerializerInterface $serializer): JsonResponse
    {
        $requestData = $request->getContent();
        $updatedAdminDTO = $serializer->deserialize($requestData, AdminDTO::class, "json");
        try {
            $admin = $this->adminServices->updateAdmin($updatedAdminDTO, $id);
        }
        catch (Exception $e) {
                return $this->json($e->getMessage());
            }

        return $this->json($admin, 200, ['message' => 'Admin updated!'], CustomGroups::$contextRead);
    }

    /**
     * @param $id
     * @return JsonResponse|null
     */
    #[Route('/admins/{id} ', name: 'delete_admin', methods: ['DELETE'])]
    public function deleteAdmin($id): ?JsonResponse
    {
        try {
            $admin = $this->adminServices->deleteAdmin($id);
        } catch (Exception $e) {
            return $this->json($e->getMessage());
        }

        return $this->json($admin, 200, ['message' => 'Admin deleted successfully'], CustomGroups::$contextRead);
    }
}
