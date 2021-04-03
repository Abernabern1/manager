<?php

namespace App\Model\User\UseCase\ResetPassword\Confirm;

use App\Model\Flusher;
use App\Model\User\Repository\PasswordResetRepository;
use App\Model\User\Repository\UserRepository;
use App\Model\User\Service\PasswordHasher;

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
     * @var PasswordHasher
     */
    private $passwordHasher;

    /**
     * @var Flusher
     */
    private $flusher;

    public function __construct(
        PasswordResetRepository $passwordResets,
        UserRepository $users,
        PasswordHasher $passwordHasher,
        Flusher $flusher
    )
    {
        $this->users = $users;
        $this->passwordResets = $passwordResets;
        $this->passwordHasher = $passwordHasher;
        $this->flusher = $flusher;
    }

    public function handle(Command $command): void
    {
        $passwordReset = $this->passwordResets->getByToken($command->token);
        $user = $this->users->get($passwordReset->getUser()->getId());

        $user->changePassword($this->passwordHasher->hash($command->password));
        $this->passwordResets->remove($passwordReset);

        $this->flusher->flush();
    }
}