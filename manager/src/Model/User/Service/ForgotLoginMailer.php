<?php

namespace App\Model\User\Service;

use App\Model\User\Entity\User;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;

class ForgotLoginMailer
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

    public function mail(User $user): void
    {
        $email = (new Email())
            ->to($user->getEmail()->getValue())
            ->subject('Forgot login')
            ->html($this->twig->render('mail/user/forget_login.html.twig', ['login' => $user->getLogin()->getValue()]));

        $this->mailer->send($email);
    }
}