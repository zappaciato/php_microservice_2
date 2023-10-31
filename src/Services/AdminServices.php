<?php

namespace App\Services;

use App\Entity\Admin;
use App\Repository\AdminRepository;
use Cassandra\Uuid;
use PhpParser\Node\Expr\Array_;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminServices
{
    private AdminRepository $adminRepository;

    public function __construct(AdminRepository $adminRepository)
    {
        $this->adminRepository = $adminRepository;

    }

    /**
     * @return array<Admin|null>
     */
    public function getAllAdmins(): array
    {
        return $this->adminRepository->findAll();

    }

    public function createAdmin(): Admin|Array
    {
        if($this->validateData($userDto))
        {   //return null; tak bylo ale wtedy wyrzucalo null w razie bledow
            return $this->validateData($userDto);
        }

        $user = new UserCreationStrategyFactory($userDto, $this->userCreator);
        $this->userCreator->setStrategy($user->createUserStrategy());

        $user = $this->userCreator->create($userDto, $this->userRepository);

        return $user;
    }

}