<?php

namespace App\Interfaces;
interface CodeAnalyzerInterface
{
    public function validateCode() : CodeAnalyzerInterface;
    public function refactorCode() : static;

    public function extractInformationFromRefactoredCode(): string;

}