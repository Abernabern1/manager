<?php

namespace App\Model\User\Service;

class PasswordHasher
{
    private $hasherAlgo;

    public function hash($password, $passwordRepeat): string
    {
        if($password !== $passwordRepeat) {
            throw new \InvalidArgumentException('Passwords are not equal.');
        }

        return password_hash($password, PASSWORD_ARGON2I);
    }
}