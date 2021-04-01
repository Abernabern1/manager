<?php

namespace App\Model\User\UseCase\ResetPassword\Request;

use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    /**
     * @var string
     */
    public $login;

    /**
     * @var string
     */
    public $oldPassword;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min=6)
     */
    public $newPassword;
}