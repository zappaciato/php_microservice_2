<?php

namespace App\Controller;

use App\DTO\AdminDTO;
use App\Entity\Admin;
use App\Entity\File;
use App\Repository\FileRepository;
use App\Services\AdminServices;
use App\Services\FileService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;
use Symfony\Component\Serializer\Encoder\JsonDecode;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Constraints\DateTime;

class AdminController extends AbstractController
{
    public function __construct(private readonly AdminServices $adminServices)
    {

    }

    /**
     * @return JsonResponse
     */
    #[Route('/admins', name: 'app_admins', methods: ['GET'])]
    public function index(SerializerInterface $serializer): JsonResponse
    {
        $admins = $this->adminServices->getAllAdmins();

        if(count($admins) === 0) {
            return new JsonResponse(['message' => 'No users found'], 404); //no content status/ dobrze to zrobic HTTP::
        }
        $context = [
            'groups' => ['read'],
            'max_depth' => 2,
        ];
        $adminsJson = $serializer->serialize($admins, 'json', $context);
        return new JsonResponse(json_decode($adminsJson));
    }



    #[Route('/admins/{id}/files', name: 'admin_files', methods: ['GET'])]
    public function getAdminFiles(string $id): JsonResponse
    {
        $admin = $this->adminServices->findAdminById($id);
//        $files = $admin->getFiles();//circular reference error with this way
        $files = $this->adminServices->getAdminFiles($admin); //równie circular reference

        if(count($files) === 0) {

            return new JsonResponse(['message' => 'Files not found', 'files' => $files], 404);
        }

        return $this->json($files, 200, ['message' => 'Success!'], []);
    }

    #[Route('/admins/files', name: 'all_files', methods: ['GET'])]
    public function getAllFiles(): JsonResponse
    {
        $files = $this->adminServices->getAllFiles();

        $count = count($files);

        if(count($files) === 0) {

            return new JsonResponse(['message' => 'Files not found'], 404);
        }

        return $this->json($files, 200, ['message' => 'Success!', 'count' => $count], []);
    }

    /**
     * @param string $id
     * @return JsonResponse
     */

    // /admins?id=809-89-080
    #[Route('/admins/{id}', name: 'app_admin', methods: ['GET'])]
    public function getAdminById(string $id): JsonResponse
    {
        $admin = $this->adminServices->findAdminById($id);
        if(empty($admin)) {

            return new JsonResponse(['message' => 'Admin not found', 'admin' => $admin], 404);
        }

        //link to files
//        $this->getAdminFiles($admin->getId())

        return $this->json($admin, 200, ['message' => 'Admin found!!!'], []);
    }

    #[Route('/admins ', name: 'create_admin', methods: ['POST'])]
    public function createAdmin(Request $request, SerializerInterface $serializer): JsonResponse
    {
        $requestData = $request->request->all();
        $file =  $request->files->get('file');
//    dd($requestData); request get content type format. TODO
        $adminData = $serializer->denormalize($requestData, AdminDTO::class, "array");

        $admin = $this->adminServices->createAdmin($adminData, $file);
        $context = [
            'groups' => ['read'],

        ];
        $adminJson = $serializer->serialize($admin, 'json', $context);

    return new JsonResponse(json_decode($adminJson));
    }

    #[Route('/admins/{id}/files ', name: 'admin_file', methods: ['POST'])]
    public function addFile(Request $request, $id): JsonResponse
    {

        $file =  $request->files->get('file');
        $admin = $this->adminServices->findAdminById($id);
        $this->adminServices->saveAdminFile($file, $admin);

        return $this->json($id, 200, ['message' => 'New Admin added!'], []);

    }

    #[Route('/admins/{adminId}/files/{fileId}/delete ', name: 'delete_file', methods: ['DELETE'])]
    public function removeFile($adminId, $fileId): JsonResponse
    {
        return $this->adminServices->removeFile($adminId, $fileId);

    }


    /**
     * @param Request $request
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    #[Route('/admins/{id} ', name: 'update_admin', methods: ['PUT'])]
    public function updateAdmin($id, Request $request, SerializerInterface $serializer): JsonResponse
    {
//        try {
            $updatedAdminDTO = $serializer->deserialize($request->getContent(), AdminDTO::class, "json");
            $admin = $this->adminServices->updateAdmin($updatedAdminDTO, $id);

//        } catch(Exception $e) {
//
//            return $this->json('Unforseen Error Occurred!'.$e);
//        }

        return $this->json($admin, 200, ['message' => 'Admin updated!'], []);
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

        return $this->json($admin, 200, ['message' => 'Admin deleted successfully'], []);
    }
}
