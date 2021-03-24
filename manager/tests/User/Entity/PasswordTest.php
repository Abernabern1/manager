<?php

namespace App\Tests\User\Entity;

use App\Model\User\Entity\Password;
use App\Model\User\Service\PasswordHasher;
use PHPUnit\Framework\TestCase;

class PasswordTest extends TestCase
{
    public function testPasswordsDiffer(): void
    {
        $this->expectExceptionMessage('Passwords are not equal.');

        new Password(
            new PasswordHasher(PASSWORD_ARGON2I),
            'password1',
            'password2'
        );
    }

    public function testPasswordHashed(): void
    {
        $passwordValue = 'password';

        $password = new Password(
            new PasswordHasher(PASSWORD_ARGON2I),
            $passwordValue,
            $passwordValue
        );

        $this->assertNotEquals($passwordValue, $password->getValue());
    }
}
