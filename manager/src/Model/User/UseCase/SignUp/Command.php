<?php

namespace App\Model\User\UseCase\SignUp;

use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    public $email;
    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min=3)
     */
    public $login;
    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min=6)
     */
    public $password;
    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min=6)
     */
    public $passwordRepeat;
}