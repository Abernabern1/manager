<?php

namespace App\Model\User\Entity\Password;

use DateTimeImmutable;

class PasswordDateTime
{
    public const INTERVAL = 'PT5M';
    public const EXPIRE_TIME = 'P1D';

    /**
     * @var DateTimeImmutable
     */
    private $value;

    public function __construct(DateTimeImmutable $value = null)
    {
        $this->value = $value ?? new DateTimeImmutable();
    }

    public function getValue(): DateTimeImmutable
    {
        return $this->value;
    }

    public function timeoutIsOut(DateTimeImmutable $currentTime): bool
    {
        $timeoutOutAt = $this->value->add(new \DateInterval(self::INTERVAL));

        return $currentTime > $timeoutOutAt;
    }

    public function isExpired(DateTimeImmutable $currentTime): bool
    {
        $expireAt = $this->value->add(new \DateInterval(self::EXPIRE_TIME));

        return $currentTime > $expireAt;
    }
}