<?php

namespace App\Controller\Profile;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Model\User\UseCase\ChangePassword;
use App\Model\User\UseCase\ChangePassword\Request\Handler as RequestHandler;
use App\Model\User\UseCase\ChangePassword\Confirm\Handler as ConfirmHandler;

/**
 * @Route("/profile", name="profile.")
 */
class PasswordChangeController extends AbstractController
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
     * @Route("/password_change", name="password_change.request")
     * @param Request $request
     * @return Response
     */
    public function request(Request $request): Response
    {
        $command = new ChangePassword\Request\Command();
        $command->login = $this->getUser()->getLogin();
        $form = $this->createForm(ChangePassword\Request\Form::class, $command);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            try {
                $this->requestHandler->handle($command);
                $this->addFlash('success', 'Check your email to confirm password change.');
            } catch (\DomainException $e) {
                $this->addFlash('error', $e->getMessage());
            }
            return $this->redirectToRoute('auth.password_change.request');
        }

        return $this->render('profile/password_change.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/password_change/{token}", name="password_change.confirm")
     * @param string $token
     * @return Response
     */
    public function confirm(string $token): Response
    {
        $command = new ChangePassword\Confirm\Command();
        $command->login = $this->getUser()->getLogin();
        $command->token = $token;

        try {
            $this->confirmHandler->handle($command);
            $this->addFlash('success', 'Password is successfully changed.');
        } catch (\DomainException $e) {
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('auth.password_change.request');
    }
}
