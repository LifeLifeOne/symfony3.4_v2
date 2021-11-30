<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function indexAction(Request $request)
    {
        return $this->render('@App/Default/home.html.twig');
    }

    /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request)
    {
        $email = null;
        $password = null;

        if ($request->isMethod('POST')) {

            $email = $request->get('email');
            $password = $request->get('password');

            if ($email == 'admin2021@gmail.com' && $password == 'admin123') {
                return $this->redirectToRoute('articles_list');
            } else {
                $err = "Identifiants incorrect !";
            }
        }

        return $this->render('@App/Default/login.html.twig', [
            "err" => @$err
        ]);
    }
}
