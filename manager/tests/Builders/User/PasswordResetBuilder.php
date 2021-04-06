<?php

namespace App\Tests\Builders\User;

use App\Model\User\Entity\Password\PasswordReset;
use App\Model\User\Entity\Password\PasswordDateTime;
use App\Model\User\Entity\User;

class PasswordResetBuilder
{
    /**
     * @var User
     */
    private $user;

    /**
     * @var string
     */
    private $token;

    /**
     * @var PasswordDateTime
     */
    private $dateTime;

    public static function make(User $user = null, string $token = null, PasswordDateTime $dateTime = null): self
    {
        $builder = new self();

        $builder->user = $user ?? UserBuilder::signUp()->confirmed()->build();
        $builder->token = $token ?? 'token';
        $builder->dateTime = $dateTime ?? new PasswordDateTime();

        return $builder;
    }

    public function withUser(User $user): self
    {
        $clone = clone $this;
        $clone->user = $user;

        return $clone;
    }

    public function withToken(string $token): self
    {
        $clone = clone $this;
        $clone->token = $token;

        return $clone;
    }

    public function withDateTime(PasswordDateTime $dateTime): self
    {
        $clone = clone $this;
        $clone->dateTime = $dateTime;

        return $clone;
    }

    public function build(): PasswordReset
    {
        return new PasswordReset(
            $this->user,
            $this->token,
            $this->dateTime
        );
    }
}