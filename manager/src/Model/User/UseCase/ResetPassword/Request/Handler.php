<?php

namespace App\Model\User\UseCase\ResetPassword\Request;

use App\Model\Flusher;
use App\Model\User\Entity\Password\PasswordDateTime;
use App\Model\User\Entity\Password\PasswordReset;
use App\Model\User\Entity\User;
use App\Model\User\Repository\PasswordResetRepository;
use App\Model\User\Repository\UserRepository;
use App\Model\User\Service\PasswordResetMailer;
use App\Model\User\Service\Tokenizer;

class Handler
{
    /**
     * @var UserRepository
     */
    private $users;

    /**
     * @var PasswordResetRepository
     */
    private $passwordResets;

    /**
     * @var Tokenizer
     */
    private $tokenizer;

    /**
     * @var PasswordResetMailer
     */
    private $mailer;

    /**
     * @var Flusher
     */
    private $flusher;

    public function __construct(
        UserRepository $users,
        PasswordResetRepository $passwordResets,
        Tokenizer $tokenizer,
        PasswordResetMailer $mailer,
        Flusher $flusher
    )
    {
        $this->users = $users;
        $this->passwordResets = $passwordResets;
        $this->tokenizer = $tokenizer;
        $this->mailer = $mailer;
        $this->flusher = $flusher;
    }

    public function handle(Command $command): void
    {
        $user = $this->users->getByEmail($command->email);

        $this->passwordResetAlreadyExists($user);

        $passwordReset = new PasswordReset(
            $user,
            $this->tokenizer->make(),
            new PasswordDateTime()
        );

        $this->passwordResets->add($passwordReset);
        $this->flusher->flush();
        $this->mailer->mail($user->getEmail(), $passwordReset->getToken());
    }

    private function passwordResetAlreadyExists(User $user): void
    {
        if($passwordReset = $this->passwordResets->findByUser($user)) {
            $passwordReset->tokenTimeoutIsOut(new \DateTimeImmutable());
            $this->passwordResets->remove($passwordReset);
            $this->flusher->flush();
        }
    }
}