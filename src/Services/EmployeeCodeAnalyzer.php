<?php

namespace App\Services;

use App\Interfaces\CodeAnalyzerInterface;

class EmployeeCodeAnalyzer implements CodeAnalyzerInterface
{
    private string $employeeCode;
    public function __construct($employeeCode)
    {
        $this->employeeCode = $employeeCode;
    }
    public function validateCode(): static
    {
        //validation logic
        $pattern = '/^\d{2}[A-Z]{2}\d{2}$/';
        if(!preg_match($pattern, $this->employeeCode)) {

            return throw new \Exception('Employee code is in the wrong format!');
        }

        return $this;
    }
    public function refactorCode(): static
    {
        $this->employeeCode = strrev($this->employeeCode);
        //other refactor logic
        return $this;

    }

    public function extractInformationFromRefactoredCode(): string
    {
        $this->employeeCode = strtoupper($this->employeeCode);
        //some dummy logic here;
        if(!strtoupper($this->employeeCode)) {
            throw new \Exception('Extraction failed.');
        };
        return "SuperAdmin";
    }
}