<?php

namespace App\Tests\Unit\Model\User\Entity\Password;

use App\Model\User\Entity\Password\PasswordDateTime;
use PHPUnit\Framework\TestCase;

class PasswordDateTimeTest extends TestCase
{
    public function testNew(): void
    {
        $passwordDateTime = new PasswordDateTime($now = new \DateTimeImmutable());

        $this->assertEquals($now, $passwordDateTime->getValue());
    }

    public function testTokenTimeoutIsOut(): void
    {
        $passwordDateTime = new PasswordDateTime($now = new \DateTimeImmutable());

        $nowPlusTenMinutes = $now->add(new \DateInterval('PT10M'));

        $this->assertTrue($passwordDateTime->timeoutIsOut($nowPlusTenMinutes));
    }

    public function testTokenTimeoutIsNotOut(): void
    {
        $passwordDateTime = new PasswordDateTime($now = new \DateTimeImmutable());

        $nowPlusFourMinutes = $now->add(new \DateInterval('PT4M'));

        $this->assertFalse($passwordDateTime->timeoutIsOut($nowPlusFourMinutes));
    }

    public function testTokenIsNotExpired(): void
    {
        $passwordDateTime = new PasswordDateTime($now = new \DateTimeImmutable());

        $nowPlusTwentyHours = $now->add(new \DateInterval('PT20H'));

        $this->assertFalse($passwordDateTime->isExpired($nowPlusTwentyHours));
    }

    public function testTokenIsExpired(): void
    {
        $passwordDateTime = new PasswordDateTime($now = new \DateTimeImmutable());

        $nowPlusOneDay = $now->add(new \DateInterval('P1DPT1M'));

        $this->assertTrue($passwordDateTime->isExpired($nowPlusOneDay));
    }
}