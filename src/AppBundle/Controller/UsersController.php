<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Users;
use AppBundle\Form\UsersType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

/**
 *@Route("/users")
 */
class UsersController extends Controller
{
    /**
     * @Route("/register", name="user_register")
     */
    public function registerAction(Request $request)
    {
        $user = new Users();
        $form = $this->createForm(UsersType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $conn = $this->getDoctrine()->getManager();
            $conn->persist($user);
            $conn->flush();

            $this->addFlash(
                'info',
                'Félicitations, vous êtes enregistré !'
            );

            return $this->redirectToRoute('user_auth');
        }

        return $this->render('@App/Users/register.html.twig', [
            'form' => $form->createView(),
            'dynamic' => 'S\'enregistrer'
        ]);
    }

    /**
     * @Route("/auth", name="user_auth")
     */
    public function authAction(Request $request)
    {
        $form = $this->createForm(UsersType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $form_DOM = $request->get('appbundle_users');
            $email = $form_DOM['email'];
            $password = $form_DOM['password'];

            $conn = $this->getDoctrine()->getManager();
            $res = $conn->getRepository(Users::class)->findOneBy(['email' => $email, 'password' => $password]);

            if (!empty($res)) {

                $session = new Session();
                $session->set('user', $res);
//                $role = $res->getRole();

//                $this->addFlash(
//                    'info',
//                    'Félicitations, vous êtes connecté !'
//                );

                return $this->redirectToRoute('articles_list');

            } else {

                $this->addFlash(
                    'error',
                    'Identification refusée !'
                );

            }
            return $this->redirectToRoute('user_auth');

        }

        return $this->render('@App/Users/auth.html.twig', [
            'form' => $form->createView(),
            'dynamic' => 'Se connecter'
        ]);
    }

    /**
     * @Route("/logout", name="user_logout")
     */
    public function logoutAction()
    {
        $session = new Session();
        $session->clear();

        return $this->redirectToRoute('user_auth');
    }

}
