<?php

namespace App\Tests\Unit\Model\User\Entity\Password;

use App\Tests\Builders\User\PasswordResetBuilder;
use App\Tests\Builders\User\UserBuilder;
use PHPUnit\Framework\TestCase;

class PasswordResetTest extends TestCase
{
    public function testNew(): void
    {
        $passwordChange = PasswordResetBuilder::make()
            ->withUser($user = UserBuilder::signUp()->confirmed()->build())
            ->withToken($token = 'token')
            ->build();

        $this->assertEquals($user, $passwordChange->getUser());
        $this->assertEquals($token, $passwordChange->getToken());
    }
}
