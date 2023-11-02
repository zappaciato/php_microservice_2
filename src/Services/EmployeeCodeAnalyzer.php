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

    /**
     * @return $this
     * @throws \Exception
     */
    public function validateCode(): static
    {
        //validation logic
        $pattern = '/^\d{2}[A-Z]{2}\d{2}$/';
        if(!preg_match($pattern, $this->employeeCode)) {

            return throw new \Exception('Employee code is in the wrong format!');
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function refactorCode(): static
    {
        $this->employeeCode = strrev($this->employeeCode);
        //other refactor logic
        return $this;

    }

    /**
     * @return string
     * @throws \Exception
     */
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