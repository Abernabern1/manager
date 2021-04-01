<?php

namespace App\Controller\Auth;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Model\User\UseCase\ResetPassword;
use App\Model\User\UseCase\ResetPassword\Request\Handler as RequestHandler;
use App\Model\User\UseCase\ResetPassword\Confirm\Handler as ConfirmHandler;

class PasswordResetController extends AbstractController
{
    /**
     * @var RequestHandler
     */
    private $requestHandler;

    /**
     * @var ConfirmHandler
     */
    private $confirmHandler;

    public function __construct(RequestHandler $requestHandler, ConfirmHandler $confirmHandler)
    {
        $this->requestHandler = $requestHandler;
        $this->confirmHandler = $confirmHandler;
    }

    /**
     * @Route("/password_reset", name="auth.password_reset.request")
     * @param Request $request
     * @return Response
     */
    public function request(Request $request): Response
    {
        $command = new ResetPassword\Request\Command();
        $command->login = $this->getUser()->getLogin();
        $form = $this->createForm(ResetPassword\Request\Form::class, $command);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            try {
                $this->requestHandler->handle($command);
                $this->addFlash('success', 'Check your email to confirm password change.');
            } catch (\DomainException $e) {
                $this->addFlash('error', $e->getMessage());
            }
            return $this->redirectToRoute('auth.password_reset.request');
        }

        return $this->render('auth/password_reset.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/password_reset/{token}", name="auth.password_reset.confirm")
     * @param string $token
     * @return Response
     */
    public function confirm(string $token): Response
    {
        $command = new ResetPassword\Confirm\Command();
        $command->login = $this->getUser()->getLogin();
        $command->token = $token;

        try {
            $this->confirmHandler->handle($command);
            $this->addFlash('success', 'Password is successfully changed.');
        } catch (\DomainException $e) {
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('auth.password_reset.request');
    }
}
