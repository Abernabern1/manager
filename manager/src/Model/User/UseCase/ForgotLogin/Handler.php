<?php

namespace App\Model\User\UseCase\ForgotLogin;

use App\Model\User\Repository\UserRepository;
use App\Model\User\Service\ForgotLoginMailer;

class Handler
{
    /**
     * @var UserRepository
     */
    private $users;

    /**
     * @var ForgotLoginMailer
     */
    private $mailer;

    public function __construct(UserRepository $users, ForgotLoginMailer $mailer)
    {
        $this->users = $users;
        $this->mailer = $mailer;
    }

    public function handle(Command $command): void
    {
        $user = $this->users->getByEmail($command->email);
        $this->mailer->mail($user);
    }
}