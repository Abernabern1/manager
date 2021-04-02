<?php

namespace App\Controller\Profile;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/profile", name="profile.")
 */
class HomeController extends AbstractController
{
    /**
     * @Route("", name="home")
     * @return Response
     */
    public function home(): Response
    {
        return $this->render('profile/home.html.twig');
    }
}
