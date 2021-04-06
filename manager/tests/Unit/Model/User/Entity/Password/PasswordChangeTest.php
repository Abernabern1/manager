<?php

namespace App\Tests\Unit\Model\User\Entity\Password;

use App\Tests\Builders\User\PasswordChangeBuilder;
use App\Tests\Builders\User\UserBuilder;
use PHPUnit\Framework\TestCase;

class PasswordChangeTest extends TestCase
{
    public function testUserChangePassword(): void
    {
        $user = UserBuilder::signUp()->build();

        $user->changePassword($newPassword = 'new_password');

        $this->assertEquals($newPassword, $user->getPassword());
    }

    public function testNew(): void
    {
        $passwordChange = PasswordChangeBuilder::make()
            ->withUser($user = UserBuilder::signUp()->confirmed()->build())
            ->withPassword($newPassword = 'new_password')
            ->withToken($token = 'token')
            ->build();

        $this->assertEquals($user, $passwordChange->getUser());
        $this->assertEquals($newPassword, $passwordChange->getPassword());
        $this->assertEquals($token, $passwordChange->getToken());
    }
}
