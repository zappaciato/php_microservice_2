<?php
namespace App\DTO;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints as Assert;



class AdminDTO
{


    #[Groups('adminDTO')]
    #[Assert\NotNull, NotBlank]
    #[Length(max: 9)]
    public string $firstName = '';

    #[Groups('adminDTO')]
    #[Assert\NotNull]
    #[NotBlank]
    public string $secondName = '';

    #[Groups('adminDTO')]
    #[Assert\NotNull]
    #[NotBlank]
    #[Email(
        message: 'The email {{ value }} is not a valid email.',
    )]
    public string $email = '';

    #[Groups('adminDTO')]
    #[Assert\NotNull]
    #[NotBlank]
    #[Type('string')]
    #[Length(max: 9, maxMessage:'The value is too long. Max length is {{limit}}.')]
//    #[Regex(
//        pattern: '/^\d{2}[A-Z]{2}\d{2}$/', //example employee number: 34CP87
//        message: 'The employee number is not in the expected format.: i.e. "34CP87"'
//    )]
    public string $employeeCode = '';

//    #[Type('FileDTO')]
//    /** @var $files FileDTO<FileDTO>  */
//    public FileDTO $files;
    #[Groups('admin')]
    #[Type('array')]
    /** @var $files array<FileDTO>  */
    public array $files;
}