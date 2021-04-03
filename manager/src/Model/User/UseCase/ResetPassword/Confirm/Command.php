<?php

namespace App\Model\User\UseCase\ResetPassword\Confirm;

use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min=6)
     */
    public $password;

    public $token;
}