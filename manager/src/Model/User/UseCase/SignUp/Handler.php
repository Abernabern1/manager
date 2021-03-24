<?php

namespace App\Model\User\UseCase\SignUp;

use App\Model\Flusher;
use App\Model\User\Entity\Email;
use App\Model\User\Entity\Login;
use App\Model\User\Entity\Password;
use App\Model\User\Entity\User;
use App\Model\User\Repositories\UserRepository;
use App\Model\User\Service\ConfirmTokenizer;
use App\Model\User\Service\PasswordHasher;
use App\Model\User\Service\SignUpConfirmTokenMailer;

class Handler
{
    /**
     * @var PasswordHasher
     */
    private $passwordHasher;
    /**
     * @var ConfirmTokenizer
     */
    private $confirmTokenizer;
    /**
     * @var UserRepository
     */
    private $users;
    /**
     * @var Flusher
     */
    private $flusher;
    /**
     * @var SignUpConfirmTokenMailer
     */
    private $mailer;

    public function __construct(
        PasswordHasher $passwordHasher,
        ConfirmTokenizer $confirmTokenizer,
        UserRepository $users,
        Flusher $flusher,
        SignUpConfirmTokenMailer $mailer
    )
    {
        $this->passwordHasher = $passwordHasher;
        $this->confirmTokenizer = $confirmTokenizer;
        $this->users = $users;
        $this->flusher = $flusher;
        $this->mailer = $mailer;
    }

    public function signUp(Command $command): void
    {
        $user = new User(
            $email = new Email($command->email),
            new Login($command->login),
            new Password($this->passwordHasher, $command->password, $command->repeatPassword),
            $token = $this->confirmTokenizer->make()
        );

        $this->users->add($user);
        $this->flusher->flush();

        $this->mailer->mail($email, $token);
    }
}