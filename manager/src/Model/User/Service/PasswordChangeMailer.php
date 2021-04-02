<?php

namespace App\Model\User\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;
use App\Model\User\Entity\Email as EmailUser;

class PasswordChangeMailer
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

    public function mail(EmailUser $emailTo, string $changeToken): void
    {
        $email = (new Email())
            ->to($emailTo->getValue())
            ->subject('Change password confirmation')
            ->html($this->twig->render('mail/user/password_change.html.twig', ['token' => $changeToken]));

        $this->mailer->send($email);
    }
}