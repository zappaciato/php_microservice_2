<?php

namespace App\Controller;

use App\Services\AdminServices;
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

    #[Route('/admins', name: 'app_admins', methods: ['GET'])]
    public function index(Request $request): JsonResponse
    {
        $admins= $this->adminServices->getAllAdmins();

        return $this->json($admins);
    }

//    #[Route('/admin/{id}', name: 'app_admin')]
//    public function getUserById(Request $request, uuid $id): JsonResponse
//    {
//        $user = $this->userServices->getUserById($id);
//
//        return $this->json($user);
//    }

    #[Route('/admins ', name: 'create_admin', methods: ['POST'])]
    public function createAdmin(Request             $request,
                               SerializerInterface $serializer): JsonResponse
    {

//        $userData = $serializer->deserialize($request->getContent(), UserDTO::class, "json");

        $user = $this->userServices->createUser($userData);

        return $this->json($user);
    }
}
