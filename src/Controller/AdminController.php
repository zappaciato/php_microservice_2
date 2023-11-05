<?php

namespace App\Controller;

use App\DTO\AdminDTO;
use App\Entity\Admin;
use App\Entity\File;
use App\Repository\FileRepository;
use App\Services\AdminServices;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Constraints\DateTime;

class AdminController extends AbstractController
{
    public function __construct(private readonly AdminServices $adminServices)
    {

    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('/admins', name: 'app_admins', methods: ['GET'])]
    public function index(Request $request): JsonResponse
    {
        $admins = $this->adminServices->getAllAdmins();

        if(count($admins) === 0) {
            return new JsonResponse(['message' => 'No users found'], 404); //no content status/ dobrze to zrobic HTTP::
        }

        return $this->json($admins);
    }

    /**
     * @param string $id
     * @return JsonResponse
     */
    #[Route('/admins/{id}', name: 'app_admin', methods: ['GET'])]
    public function getUserById(string $id): JsonResponse
    {
        $admin = $this->adminServices->findAdminById($id);
        if(empty($admin)) {

            return new JsonResponse(['message' => 'Admin not found', 'admin' => $admin], 404);
        }

        return $this->json($admin, 200, ['message' => 'Admin found!!!'], []);
    }

    /**
     * @param Request $request
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    #[Route('/admins ', name: 'create_admin', methods: ['POST'])]
    public function createAdmin(Request $request, SerializerInterface $serializer): JsonResponse
    {
        $requestData = $request->request->all();
        $file =  $request->files->get('file');

        $adminData = $serializer->denormalize($requestData, AdminDTO::class, "array", ['groups' => 'adminDTO']);

        $admin = $this->adminServices->createAdmin($adminData, $file);

        return $this->json($admin, 200, ['message' => 'New Admin added!'], []);

    }



    /**
     * @param Request $request
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    #[Route('/admins/{id} ', name: 'update_admin', methods: ['PUT'])]
    public function updateAdmin($id, Request $request, SerializerInterface $serializer): JsonResponse
    {
        try {
            $updatedAdminDTO = $serializer->deserialize($request->getContent(), AdminDTO::class, "json");
            $admin = $this->adminServices->updateAdmin($updatedAdminDTO, $id);

        } catch(Exception $e) {

            return $this->json('Unforseen Error Occurred!'.$e);
        }

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
