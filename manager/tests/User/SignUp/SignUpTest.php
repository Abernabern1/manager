<?php

namespace App\Tests\User\SignUp;

use App\Model\User\Entity\Email;
use App\Model\User\Entity\Login;
use App\Model\User\Entity\User;
use App\Model\User\Service\ConfirmTokenizer;
use App\Model\User\Service\PasswordHasher;
use PHPUnit\Framework\TestCase;

class SignUpTest extends TestCase
{
    public function testSuccess(): void
    {
        $passwordHasher = new PasswordHasher(PASSWORD_ARGON2I);
        $tokenizer = new ConfirmTokenizer();

        $user = new User(
            $email = new Email('email@email.email'),
            $login = new Login('login'),
            $password = $passwordHasher->hash('password', 'password'),
            $token = $tokenizer->make()
        );

        $this->assertEquals($email, $user->getEmail());
        $this->assertEquals($login, $user->getLogin());
        $this->assertEquals($password, $user->getPassword());
        $this->assertEquals($token, $user->getConfirmToken());

        $this->assertTrue($user->statusIsWaiting());
        $this->assertFalse($user->statusIsActive());

        $this->assertTrue($user->roleIsUser());
    }
}
