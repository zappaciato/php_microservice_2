<?php

namespace App\Services;

use App\Entity\Admin;
use App\DTO\AdminDTO;
use App\interfaces\AdminCreatorStrategyInterface;
use App\Interfaces\AdminStrategyInterface;
use App\Repository\AdminRepository;
use App\Repository\FileRepository;

class AdminCreator
{

    /**
     * @var AdminCreatorStrategyInterface
     */
    private AdminCreatorStrategyInterface $adminStrategy;
    /**
     * @var AdminRepository
     */
    private AdminRepository $adminRepository;

    /**
     * @param AdminRepository $adminRepository
     */
    public function __construct(AdminRepository $adminRepository)
    {
        $this->adminRepository = $adminRepository;

    }

    /**
     * @param AdminCreatorStrategyInterface $strategy
     * @return $this
     */
    public function setStrategy(AdminCreatorStrategyInterface $strategy) : self
    {
        $this->adminStrategy = $strategy;
        return $this;
    }

    public function getStrategy(): AdminCreatorStrategyInterface
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