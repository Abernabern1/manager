<?php

namespace App\Tests\User\Service;

use App\Model\User\Service\PasswordHasher;
use PHPUnit\Framework\TestCase;

class PasswordHasherTest extends TestCase
{
    public function testPasswordsDiffer(): void
    {
        $passwordHasher = new PasswordHasher(PASSWORD_ARGON2I);

        $this->expectExceptionMessage('Passwords are not equal.');

        $passwordHasher->hash(
            'password1',
            'password2'
        );
    }

    public function testPasswordHashed(): void
    {
        $passwordValue = 'password';
        $passwordHasher = new PasswordHasher(PASSWORD_ARGON2I);

        $password = $passwordHasher->hash(
            $passwordValue,
            $passwordValue
        );

        $this->assertNotEquals($passwordValue, $password);
    }
}
