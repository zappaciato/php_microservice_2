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

        if(empty($admins)) {
            return new JsonResponse(['message' => 'No users found'], 404);
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
//        return new JsonResponse(['message' => 'Admin found!!!', 'admin' => $admin->getEmail()], 200);
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
        $requestData = [
            'firstName'     => $request->request->get('firstName'),
            'secondName'    => $request->request->get('secondName'),
            'email'         => $request->request->get('email'),
            'employeeCode'  => $request->request->get('employeeCode')
        ];

        $files =  $request->files;
        $fileData = [];
        foreach ($files as $file) {
            $fileName = $file->getClientOriginalName();
//            $fileName = $file->getClientOriginalName() .uniqid(). $file->guessExtension();
            $path = 'Files/';

            $fileData = [
                'fileName' => $fileName,
                'path' => $path,
                'uploadDate' => '22-21-23'
,
            ];
//            $file->move($fileData['path'], $fileData['fileName']);

        }
        $requestData['files'] = $fileData;
        $requestData = json_encode($requestData);
        $adminData = $serializer->deserialize($requestData, AdminDTO::class, "json", ['groups' => 'adminDTO']);
        $admin = $this->adminServices->createAdmin($adminData);

        return $this->json($admin, 200, [], []);

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

//        return new JsonResponse(['message' => 'Admin updated!', 'admin' => $admin->getEmail()], 200);
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

//        return new JsonResponse(['message' => 'Admin deleted successfully', 'admin' => $admin->getEmail()], 200);
        return $this->json($admin, 200, ['message' => 'Admin deleted successfully'], []);
    }

//    #[Route('/admins/athorized ', name: 'delete_admin', methods: ['GET'])]
//    public function getAdminAuthList($id): ?JsonResponse
//    {
//        try {
//            $admins= $this->adminServices->getAllAdmins();
//        } catch (Exception $e) {
//            return $this->json($e->getMessage());
//        }
//
//        return $this->json('success');
//    }

}
