<?php

namespace App\Services;

use App\Entity\Admin;
use App\DTO\AdminDTO;
use App\interfaces\AdminCreatorStrategyInterface;
use App\Interfaces\AdminStrategyInterface;
use App\Repository\AdminRepository;

class AdminCreator
{

    private AdminCreatorStrategyInterface $adminStrategy;
    private AdminRepository $adminRepository;

    public function __construct(AdminRepository $adminRepository)
    {

        $this->adminRepository = $adminRepository;
    }

    public function setStrategy(AdminCreatorStrategyInterface $strategy) : void
    {
        $this->adminStrategy = $strategy;

    }

    public function getStrategy()
    {
        return $this->adminStrategy;
    }

    public function createAdmin(AdminDTO $adminDto): Admin
    {
        $admin = $this->adminStrategy->createAdmin($adminDto);
        $this->adminRepository->save($admin);

        return $admin;

    }
}