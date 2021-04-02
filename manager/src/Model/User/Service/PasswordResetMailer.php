<?php

namespace App\Model\User\Service;

use App\Model\User\Entity\Email as EmailUser;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;

class PasswordResetMailer
{
    /**
     * @var MailerInterface
     */
    private $mailer;
    /**
     * @var Environment
     */
    private $twig;

    public function __construct(MailerInterface $mailer, Environment $twig)
    {
        $this->twig = $twig;
        $this->mailer = $mailer;
    }

    public function mail(EmailUser $emailTo, string $resetToken): void
    {
        $email = (new Email())
            ->to($emailTo->getValue())
            ->subject('Password reset confirmation')
            ->html($this->twig->render('mail/user/password_reset.html.twig', ['token' => $resetToken]));

        $this->mailer->send($email);
    }
}