<?php

namespace App\Services;

use App\Admins\Admin;
use App\DTO\AdminDTO;
use App\interfaces\AdminCreatorStrategyInterface;

class AdminStrategyFactory
{
    private AdminDTO $adminData;
//    private AdminCreatorStrategyInterface $strategy;

    public function __construct(AdminDTO $adminData)
    {
        $this->adminData = $adminData;

    }

    public function createAdminStrategy(): AdminCreatorStrategyInterface
    {
//        $strategy = match (true) {
//            Roles::analyzeEmail($this->userDto) && Roles::analyzePhoneNumber($this->userDto)    => new AdminUserStrategy(),
//            Roles::analyzeEmail($this->userDto) || Roles::analyzePhoneNumber($this->userDto)    => new VipUserStrategy(),
//            default                                                                             => new SimpleUserStrategy()
//
//        };
        $strategy = new Admin();
echo "jestem w create admin staregy";
        return $strategy;

    }

}