<?php

namespace App\Model\User\Entity;

class User
{
    public const STATUS_WAITING = 'waiting';
    public const STATUS_ACTIVE = 'active';

    /**
     * @var Email
     */
    private $email;
    /**
     * @var Login
     */
    private $login;
    /**
     * @var Password
     */
    private $password;
    /**
     * @var string
     */
    private $status;
    /**
     * @var Role
     */
    private $role;
    /**
     * @var ConfirmToken
     */
    private $confirmToken;

    public function __construct(Email $email, Login $login, Password $password, ConfirmToken $confirmToken)
    {
        $this->email = $email;
        $this->login = $login;
        $this->password = $password;
        $this->confirmToken = $confirmToken;

        $this->status = self::STATUS_WAITING;
        $this->role = new Role(Role::USER);
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getLogin(): Login
    {
        return $this->login;
    }

    public function getPassword(): Password
    {
        return $this->password;
    }

    public function getConfirmToken(): ConfirmToken
    {
        return $this->confirmToken;
    }

    public function getRole(): Role
    {
        return $this->role;
    }

    public function statusIsWaiting(): bool
    {
        if($this->status === self::STATUS_WAITING) {
            return true;
        }

        return false;
    }

    public function statusIsActive(): bool
    {
        if($this->status === self::STATUS_ACTIVE) {
            return true;
        }

        return false;
    }

    public function roleIsUser(): bool
    {
        return $this->role->isUser();
    }
}