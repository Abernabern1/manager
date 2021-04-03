<?php

namespace App\Controller\Auth;

use App\ModelReader\User\PasswordResetFetcher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Model\User\UseCase\ResetPassword\Request as ResetPasswordRequest;
use App\Model\User\UseCase\ResetPassword\Confirm as ResetPasswordConfirm;

/**
 * @Route("password_reset", name="auth.password_reset.")
 */
class PasswordResetController extends AbstractController
{
    /**
     * @var ResetPasswordRequest\Handler
     */
    private $requestHandler;

    /**
     * @var ResetPasswordConfirm\Handler
     */
    private $confirmHandler;

    /**
     * @var PasswordResetFetcher
     */
    private $passwordResetFetcher;

    public function __construct(
        ResetPasswordRequest\Handler $requestHandler,
        ResetPasswordConfirm\Handler $confirmHandler,
        PasswordResetFetcher $passwordResetFetcher
    )
    {
        $this->requestHandler = $requestHandler;
        $this->confirmHandler = $confirmHandler;
        $this->passwordResetFetcher = $passwordResetFetcher;
    }

    /**
     * @Route("", name="request")
     * @param Request $request
     * @return Response
     */
    public function request(Request $request): Response
    {
        if($this->userIsGuest()) {
            return $this->redirectToRoute('profile.home');
        }

        $command = new ResetPasswordRequest\Command();
        $form = $this->createForm(ResetPasswordRequest\Form::class, $command);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            try {
                $this->requestHandler->handle($command);
                $this->addFlash('success', 'Check your email to confirm password reset.');
            } catch (\DomainException $e) {
                $this->addFlash('error', $e->getMessage());
            }
            return $this->redirectToRoute('auth.password_reset.request');
        }

        return $this->render('auth/password_reset/request.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{token}", name="confirm")
     * @param string $token
     * @param Request $request
     * @return Response
     */
    public function confirm(string $token, Request $request): Response
    {
        if($this->userIsGuest()) {
            return $this->redirectToRoute('profile.home');
        }
        if(!$this->resetTokenExists($token)) {
            return $this->redirectToRoute('auth.password_reset.request');
        }

        $command = new ResetPasswordConfirm\Command();
        $form = $this->createForm(ResetPasswordConfirm\Form::class, $command);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            try {
                $this->confirmHandler->handle($command);
                $this->addFlash('success', 'Password is successfully changed.');
            } catch (\DomainException $e) {
                $this->addFlash('error', $e->getMessage());
            }
            return $this->redirectToRoute('auth.password_reset.request');
        }

        return $this->render('auth/password_reset/confirm.html.twig', [
            'form' => $form->createView(),
            'token' => $token
        ]);
    }

    private function userIsGuest(): bool
    {
        return (bool) $this->getUser();
    }

    private function resetTokenExists(string $token): bool
    {
        return $this->passwordResetFetcher->existsByToken($token);
    }
}