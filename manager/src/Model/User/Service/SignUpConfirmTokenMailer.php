<?php

namespace App\Model\User\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use App\Model\User\Entity\Email as EmailUser;
use Twig\Environment;

class SignUpConfirmTokenMailer
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

    public function mail(EmailUser $emailTo, string $token): void
    {
        $email = (new Email())
            ->to($emailTo->getValue())
            ->subject('Sign up confirmation')
            ->html($this->twig->render('mail/user/confirm_email.html.twig', ['token' => $token]));

        $this->mailer->send($email);
    }
}