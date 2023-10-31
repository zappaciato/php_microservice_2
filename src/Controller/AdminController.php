<?php

namespace App\Controller;

use App\DTO\AdminDTO;
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
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('/admins', name: 'app_admins', methods: ['GET'])]
    public function index(Request $request): JsonResponse
    {
        $admins= $this->adminServices->getAllAdmins();

        return $this->json($admins);
    }

    /**
     * @param string $id
     * @return JsonResponse
     */
    #[Route('/admins/{id}', name: 'app_admin', methods: ['GET'])]
    public function getUserById(string $id): JsonResponse
    {
        $admins= $this->adminServices->findAdminById($id);

        return $this->json($admins);
    }

    /**
     * @param Request $request
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    #[Route('/admins ', name: 'create_admin', methods: ['POST'])]
    public function createAdmin(Request $request, SerializerInterface $serializer): JsonResponse
    {
        $adminData = $serializer->deserialize($request->getContent(), AdminDTO::class, "json");

        $admin = $this->adminServices->createAdmin($adminData);

        return $this->json($admin);
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

        return $this->json($admin);

    }

    #[Route('/admins ', name: 'delete_admin', methods: ['POST'])]
    public function deleteAdmin(Request             $request,
                                SerializerInterface $serializer): JsonResponse
    {

        $adminData = $serializer->deserialize($request->getContent(), AdminDTO::class, "json");

        $admin = $this->adminServices->createAdmin($adminData);

        return $this->json($admin);
    }
}
