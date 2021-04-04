<?php

namespace App\Model\User\UseCase\SignUp;

use App\Model\Flusher;
use App\Model\User\Entity\Email;
use App\Model\User\Entity\Login;
use App\Model\User\Entity\User;
use App\Model\User\Repository\UserRepository;
use App\Model\User\Service\PasswordHasher;
use App\Model\User\Service\SignUpConfirmTokenMailer;
use App\Model\User\Service\Tokenizer;

class Handler
{
    /**
     * @var PasswordHasher
     */
    private $passwordHasher;
    /**
     * @var Tokenizer
     */
    private $tokenizer;
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
        Tokenizer $tokenizer,
        UserRepository $users,
        Flusher $flusher,
        SignUpConfirmTokenMailer $mailer
    )
    {
        $this->passwordHasher = $passwordHasher;
        $this->tokenizer = $tokenizer;
        $this->users = $users;
        $this->flusher = $flusher;
        $this->mailer = $mailer;
    }

    public function handle(Command $command): void
    {
        if($this->passwordHasher->passwordsAreEqual($command->password, $command->passwordRepeat)) {
            throw new \DomainException('Passwords are not equal.');
        }

        $user = new User(
            $email = new Email($command->email),
            new Login($command->login),
            $this->passwordHasher->hash($command->password),
            $token = $this->tokenizer->make()
        );

        $this->users->add($user);
        $this->flusher->flush();

        $this->mailer->mail($email, $token);
    }
}