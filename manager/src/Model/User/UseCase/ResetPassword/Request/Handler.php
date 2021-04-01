<?php

namespace App\Model\User\UseCase\ResetPassword\Request;

use App\Model\Flusher;
use App\Model\User\Entity\Email;
use App\Model\User\Entity\PasswordReset;
use App\Model\User\Entity\User;
use App\Model\User\Repository\PasswordResetRepository;
use App\Model\User\Repository\UserRepository;
use App\Model\User\Service\PasswordHasher;
use App\Model\User\Service\PasswordResetMailer;
use App\Model\User\Service\ResetTokenizer;

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
     * @var PasswordHasher
     */
    private $passwordHasher;

    /**
     * @var ResetTokenizer
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
        PasswordHasher $passwordHasher,
        ResetTokenizer $tokenizer,
        PasswordResetMailer $mailer,
        Flusher $flusher
    )
    {
        $this->users = $users;
        $this->passwordResets = $passwordResets;
        $this->passwordHasher = $passwordHasher;
        $this->tokenizer = $tokenizer;
        $this->mailer = $mailer;
        $this->flusher = $flusher;
    }

    public function handle(Command $command)
    {
        $user = $this->users->getByLogin($command->login);
        if(!$this->passwordHasher->isValid($command->oldPassword, $user->getPassword())) {
            throw new \DomainException('Old password is not correct');
        }

        $this->passwordResetAlreadyExists($user);

        $passwordReset = new PasswordReset(
            $user,
            $this->passwordHasher->hash($command->newPassword),
            $this->tokenizer->make(),
            new \DateTimeImmutable()
        );

        $this->passwordResets->add($passwordReset);
        $this->flusher->flush();
        $this->mailer->mail($user->getEmail(), $passwordReset->getToken());
    }

    private function passwordResetAlreadyExists(User $user): void
    {
        if($passwordReset = $this->passwordResets->findByUser($user)) {
            $passwordReset->resetTimeoutIsOut(new \DateTimeImmutable());
            $this->passwordResets->remove($passwordReset);
            $this->flusher->flush();
        }
    }
}