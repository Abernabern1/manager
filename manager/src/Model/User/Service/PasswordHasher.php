<?php

namespace App\Model\User\Service;

class PasswordHasher
{
    private $hasherAlgo;

    public function __construct($hasherAlgo)
    {
        $this->hasherAlgo = $hasherAlgo;
    }

    public function hash($password, $passwordRepeat): string
    {
        if($password !== $passwordRepeat) {
            throw new \InvalidArgumentException('Passwords are not equal.');
        }

        return password_hash($password, $this->hasherAlgo);
    }

    public function isValid($password, $hash): bool
    {
        return password_verify($password, $hash);
    }
}