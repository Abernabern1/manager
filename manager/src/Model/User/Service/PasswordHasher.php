<?php

namespace App\Model\User\Service;

class PasswordHasher
{
    private $hasherAlgo;

    public function __construct($hasherAlgo)
    {
        $this->hasherAlgo = $hasherAlgo;
    }

    public function hash($password)
    {
        return password_hash($password, $this->hasherAlgo);
    }
}