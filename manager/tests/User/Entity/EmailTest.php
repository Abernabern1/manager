<?php

namespace App\Tests\User\Entity;

use App\Model\User\Entity\Email;
use PHPUnit\Framework\TestCase;

class EmailTest extends TestCase
{
    public function testError(): void
    {
        $this->expectExceptionMessage('Incorrect email value given.');

        new Email('email');
    }

    public function testSuccess(): void
    {
        $emailValue = 'Email@Email.Email';

        $email = new Email($emailValue);

        $this->assertEquals(mb_strtolower($emailValue), $email->getValue());
    }
}
