<?php

namespace App\Tests\Unit\Model\User\Entity;

use App\Tests\Builders\User\UserBuilder;
use PHPUnit\Framework\TestCase;
use App\Model\User\Entity\Email;
use App\Model\User\Entity\Login;
use App\Model\User\Service\Tokenizer;

class SignUpTest extends TestCase
{
    public function testSuccess(): void
    {
        $user = UserBuilder::signUp(
            $email = new Email('email@email.email'),
            $login = new Login('login'),
            $password = 'password',
            $token = (new Tokenizer())->make()
        )->build();

        $this->assertEquals($email, $user->getEmail());
        $this->assertEquals($login, $user->getLogin());
        $this->assertEquals($password, $user->getPassword());
        $this->assertEquals($token, $user->getConfirmToken());

        $this->assertTrue($user->statusIsWaiting());
        $this->assertFalse($user->statusIsActive());

        $this->assertTrue($user->roleIsUser());
    }
}
