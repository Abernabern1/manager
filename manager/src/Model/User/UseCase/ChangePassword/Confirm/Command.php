<?php

namespace App\Model\User\UseCase\ChangePassword\Confirm;

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