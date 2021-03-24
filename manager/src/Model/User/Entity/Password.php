<?php

namespace App\Model\User\Entity;

use App\Model\User\Service\PasswordHasher;

class Password
{
    /**
     * @var string
     */
    private $value;

    public function __construct(PasswordHasher $passwordHasher, string $password, string $passwordRepeat)
    {
        if($password !== $passwordRepeat) {
            throw new \InvalidArgumentException('Passwords are not equal.');
        }

        $this->value = $passwordHasher->hash($password);
    }

    public function getValue()
    {
        return $this->value;
    }
}