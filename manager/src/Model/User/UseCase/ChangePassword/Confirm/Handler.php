<?php

namespace App\Model\User\UseCase\ChangePassword\Confirm;

use App\Model\Flusher;
use App\Model\User\Repository\PasswordChangeRepository;
use App\Model\User\Repository\UserRepository;

class Handler
{
    /**
     * @var PasswordChangeRepository
     */
    private $passwordChanges;

    /**
     * @var UserRepository
     */
    private $users;

    /**
     * @var Flusher
     */
    private $flusher;

    public function __construct(PasswordChangeRepository $passwordChanges, UserRepository $users, Flusher $flusher)
    {
        $this->passwordChanges = $passwordChanges;
        $this->users = $users;
        $this->flusher = $flusher;
    }

    public function handle(Command $command): void
    {
        $passwordChange = $this->passwordChanges->getByToken($command->token);
        $passwordChange->tokenIsNotExpired(new \DateTimeImmutable());

        $user = $this->users->getByLogin($command->login);
        $user->changePassword($passwordChange->getPassword());

        $this->passwordChanges->remove($passwordChange);
        $this->flusher->flush();
    }
}