<?php

namespace App\Model\User\UseCase\ChangePassword\Request;

use App\Model\Flusher;
use App\Model\User\Entity\Password\PasswordChange;
use App\Model\User\Entity\Password\PasswordDateTime;
use App\Model\User\Entity\User;
use App\Model\User\Repository\PasswordChangeRepository;
use App\Model\User\Repository\UserRepository;
use App\Model\User\Service\PasswordHasher;
use App\Model\User\Service\PasswordChangeMailer;
use App\Model\User\Service\Tokenizer;

class Handler
{
    /**
     * @var UserRepository
     */
    private $users;

    /**
     * @var PasswordChangeRepository
     */
    private $passwordChanges;

    /**
     * @var PasswordHasher
     */
    private $passwordHasher;

    /**
     * @var Tokenizer
     */
    private $tokenizer;

    /**
     * @var PasswordChangeMailer
     */
    private $mailer;

    /**
     * @var Flusher
     */
    private $flusher;

    public function __construct(
        UserRepository $users,
        PasswordChangeRepository $passwordChanges,
        PasswordHasher $passwordHasher,
        Tokenizer $tokenizer,
        PasswordChangeMailer $mailer,
        Flusher $flusher
    )
    {
        $this->users = $users;
        $this->passwordChanges = $passwordChanges;
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

        $this->passwordChangeAlreadyExists($user);

        $passwordChange = new PasswordChange(
            $user,
            $this->passwordHasher->hash($command->newPassword),
            $this->tokenizer->make(),
            new PasswordDateTime()
        );

        $this->passwordChanges->add($passwordChange);
        $this->flusher->flush();
        $this->mailer->mail($user->getEmail(), $passwordChange->getToken());
    }

    private function passwordChangeAlreadyExists(User $user): void
    {
        if($passwordChange = $this->passwordChanges->findByUser($user)) {
            $passwordChange->tokenTimeoutIsOut(new \DateTimeImmutable());
            $this->passwordChanges->remove($passwordChange);
            $this->flusher->flush();
        }
    }
}