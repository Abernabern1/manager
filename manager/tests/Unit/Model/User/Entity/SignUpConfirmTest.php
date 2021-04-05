<?php

namespace App\Tests\Unit\Model\User\Entity;

use App\Tests\Builders\User\UserBuilder;
use PHPUnit\Framework\TestCase;
use App\Model\User\Entity\Email;
use App\Model\User\Entity\Login;
use App\Model\User\Entity\User;
use App\Model\User\Service\Tokenizer;
use App\Model\User\Service\PasswordHasher;

class SignUpConfirmTest extends TestCase
{
    public function testSuccess(): void
    {
        $user = UserBuilder::signUp()->build();

        $user->activate();

        $this->assertTrue($user->statusIsActive());
        $this->assertFalse($user->statusIsWaiting());
    }

    public function testDoubleActivate(): void
    {
        $user = UserBuilder::signUp()->confirmed()->build();

        $this->expectExceptionMessage('User is not waiting for activation.');

        $user->activate();
    }
}
