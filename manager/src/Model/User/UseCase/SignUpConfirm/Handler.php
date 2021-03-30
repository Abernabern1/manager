<?php

namespace App\Model\User\UseCase\SignUpConfirm;

use App\Model\Flusher;
use App\Model\User\Repository\UserRepository;

class Handler
{
    /**
     * @var UserRepository
     */
    private $users;
    /**
     * @var Flusher
     */
    private $flusher;

    public function __construct(UserRepository $users, Flusher $flusher)
    {
        $this->users = $users;
        $this->flusher = $flusher;
    }

    public function handle(Command $command): void
    {
        $user = $this->users->getByToken($command->token);

        $user->activate();

        $this->flusher->flush();
    }
}