<?php

namespace App\Controller\Auth;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Model\User\UseCase\ResetPassword\Request as ResetPasswordRequest;

/**
 * @Route("password_reset", name="auth.password_reset.")
 */
class PasswordResetController extends AbstractController
{
    /**
     * @var ResetPasswordRequest\Handler
     */
    private $requestHandler;

    public function __construct(ResetPasswordRequest\Handler $requestHandler)
    {
        $this->requestHandler = $requestHandler;
    }

    /**
     * @Route("", name="request")
     * @param Request $request
     * @return Response
     */
    public function request(Request $request): Response
    {
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
}