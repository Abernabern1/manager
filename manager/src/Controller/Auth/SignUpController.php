<?php

namespace App\Controller\Auth;

use App\Model\User\UseCase\SignUp\Command;
use App\Model\User\UseCase\SignUp;
use App\Model\User\UseCase\SignUp\Handler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SignUpController extends AbstractController
{
    /**
     * @Route("/signup", name="auth.signup")
     * @param Request $request
     * @param Handler $handler
     * @return Response
     */
    public function signup(Request $request, Handler $handler): Response
    {
        $command = new SignUp\Command();

        $form = $this->createForm(SignUp\Form::class, $command);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            try {
                $handler->handle($command);
                $this->addFlash('success', 'You successfully signed up. Check your email.');
                return $this->redirectToRoute('auth.signup');
            } catch (\DomainException $e) {
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->render('auth/signup.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/signup-confirm/{token}", name="auth.confirm")
     * @param string $token
     * @param Request $request
     * @return Response
     */
    public function confirm(string $token, Request $request): Response
    {
        echo $token;
        die();
    }
}
