<?php

namespace App\Model\User\Service;

use App\Model\User\Entity\ConfirmToken;
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

    public function mail(EmailUser $emailTo, ConfirmToken $token): void
    {
        $email = (new Email())
            ->to($emailTo->getValue())
            ->subject('Sign up confirmation')
            ->html($this->twig->render('mail/user/signUp.html.twig.html', ['token' => $token->getValue()]));

        $this->mailer->send($email);
    }
}