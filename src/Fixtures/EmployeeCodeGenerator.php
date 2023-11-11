<?php

namespace App\Fixtures;

class EmployeeCodeGenerator
{

    public function generateCode(): string{
        return $this->generateLetters().$this->generateDigits().$this->generateLetters();
    }
    private function generateLetters(): string
    {
        $firstLetter = $this->drawLetter();
        $secondLetter = $this->drawLetter();

        if($firstLetter !== $secondLetter) {
            $letters = [$firstLetter, $secondLetter];
            return implode('', $letters);
        }

        return $this->generateLetters();

    }

    private function generateDigits(): string
    {
        $firstDigit = $this->drawDigit();
        $secondDigit = $this->drawDigit();

        if($firstDigit !== $secondDigit) {
            $digits = [$firstDigit, $secondDigit];
            return implode('', $digits);
        }

        return $this->generateDigits();

    }

    private function drawLetter(): string
    {
        $letters = range('A', 'Z');

        return $letters[array_rand($letters)];

    }

    private function drawDigit(): int
    {
        $digit = range(0, 9);

        return $digit[array_rand($digit)];
    }

}