<?php

namespace App\Controller\Auth;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Model\User\UseCase\ResetPassword;
use App\Model\User\UseCase\ResetPassword\Request\Handler as RequestHandler;

class PasswordResetController extends AbstractController
{
    /**
     * @var RequestHandler
     */
    private $requestHandler;

    public function __construct(RequestHandler $requestHandler)
    {
        $this->requestHandler = $requestHandler;
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
}
