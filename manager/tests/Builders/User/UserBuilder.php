<?php

namespace App\Tests\Builders\User;

use App\Model\User\Entity\Email;
use App\Model\User\Entity\Login;
use App\Model\User\Entity\User;

class UserBuilder
{
    /**
     * @var Email
     */
    private $email;

    /**
     * @var Login
     */
    private $login;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $token;

    /**
     * @var bool
     */
    private $confirmed;

    public static function signUp(Email $email = null, Login $login = null, string $password = null, string $token = null): self
    {
        $builder = new self();

        $builder->email = $email ?? new Email('email@email.email');
        $builder->login = $login ?? new Login('login');
        $builder->password = $password ?? 'password';
        $builder->token = $token ?? 'token';

        return $builder;
    }

    public function withEmail(Email $email): self
    {
        $clone = clone $this;
        $clone->email = $email;

        return $clone;
    }

    public function withLogin(Login $login): self
    {
        $clone = clone $this;
        $clone->login = $login;

        return $clone;
    }

    public function withPassword(string $password): self
    {
        $clone = clone $this;
        $clone->password = $password;

        return $clone;
    }

    public function withToken(string $token): self
    {
        $clone = clone $this;
        $clone->token = $token;

        return $clone;
    }

    public function confirmed(): self
    {
        $clone = clone $this;
        $clone->confirmed = true;

        return $clone;
    }

    public function build(): User
    {
        $user = new User(
            $this->email,
            $this->login,
            $this->password,
            $this->token
        );

        if($this->confirmed) {
            $user->activate();
        }

        return $user;
    }
}