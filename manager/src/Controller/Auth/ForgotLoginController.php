<?php

namespace App\Controller\Auth;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Model\User\UseCase\ForgotLogin;

class ForgotLoginController extends AbstractController
{
    /**
     * @var ForgotLogin\Handler
     */
    private $handler;

    public function __construct(ForgotLogin\Handler $handler)
    {
        $this->handler = $handler;
    }

    /**
     * @Route("/forgot_login", name="auth.forgot_password")
     * @param Request $request
     * @return Response
     */
    public function forgot(Request $request): Response
    {
        $command = new ForgotLogin\Command();
        $form = $this->createForm(ForgotLogin\Form::class, $command);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            try {
                $this->handler->handle($command);
                $this->addFlash('success', 'We have sent login to your email. Check email-address.');
            } catch (\DomainException $e) {
                $this->addFlash('error', $e->getMessage());
            }
            return $this->redirectToRoute('auth.forgot_password');
        }

        return $this->render('auth/forgot_login.html.twig', [
            'form' => $form->createView()
        ]);
    }
}