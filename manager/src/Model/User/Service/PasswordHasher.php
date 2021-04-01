<?php

namespace App\Model\User\Service;

class PasswordHasher
{
    private $hasherAlgo;

    public function __construct($hasherAlgo)
    {
        $this->hasherAlgo = $hasherAlgo;
    }

    public function hash($password): string
    {
        return password_hash($password, $this->hasherAlgo);
    }

    public function isValid($password, $hash): bool
    {
        return password_verify($password, $hash);
    }

    public function passwordsAreEqual($password1, $password2): bool
    {
        return $password1 !== $password2;
    }
}