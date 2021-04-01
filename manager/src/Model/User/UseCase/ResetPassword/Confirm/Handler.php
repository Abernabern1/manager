<?php

namespace App\Model\User\UseCase\ResetPassword\Confirm;

use App\Model\Flusher;
use App\Model\User\Entity\User;
use App\Model\User\Repository\PasswordResetRepository;
use App\Model\User\Repository\UserRepository;

class Handler
{
    /**
     * @var PasswordResetRepository
     */
    private $passwordResets;

    /**
     * @var UserRepository
     */
    private $users;

    /**
     * @var Flusher
     */
    private $flusher;

    public function __construct(PasswordResetRepository $passwordResets, UserRepository $users, Flusher $flusher)
    {
        $this->passwordResets = $passwordResets;
        $this->users = $users;
        $this->flusher = $flusher;
    }

    public function handle(Command $command): void
    {
        $passwordReset = $this->passwordResets->getByToken($command->token);
        $passwordReset->tokenIsNotExpired(new \DateTimeImmutable());

        $user = $this->users->getByLogin($command->login);
        $user->changePassword($passwordReset->getPassword());

        $this->passwordResets->remove($passwordReset);
        $this->flusher->flush();
    }
}