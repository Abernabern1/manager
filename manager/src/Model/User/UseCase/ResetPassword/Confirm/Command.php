<?php

namespace App\Model\User\UseCase\ResetPassword\Confirm;

class Command
{
    /**
     * @var string
     */
    public $login;

    /**
     * @var string
     */
    public $token;
}