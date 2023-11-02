<?php

namespace App\Services;

use App\Admins\Admin;
use App\Admins\SuperAdmin;
use App\DTO\AdminDTO;
use App\Interfaces\AdminCreatorStrategyInterface;

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
        $code = new EmployeeCodeAnalyzer($this->adminData->employeeCode);
print_r($this->adminData->employeeCode);
        return
            match ($code->validateCode()->refactorCode()->extractInformationFromRefactoredCode()) {
            "SuperAdmin"    => new SuperAdmin(),
            default         => new Admin()
        };
    }

}