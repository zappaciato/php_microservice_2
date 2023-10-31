<?php

namespace App\DTO;

class AdminDTO
{
//    #[Assert\NotNull]
//    #[NotBlank]
//    #[Length(max: 9)]
    public string $firstName = '';

//    #[Assert\NotNull]
//    #[NotBlank]
    public string $lastName = '';

//    #[Assert\NotNull]
//    #[NotBlank]
//    #[Email(
//        message: 'The email {{ value }} is not a valid email.',
//    )]
    public string $email = '';

//    #[Assert\NotNull]
//    #[NotBlank]
//    #[Type('int')]
//    #[Length(max: 9)]
    public int $phoneNumber = 0;

}