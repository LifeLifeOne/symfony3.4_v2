<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Users;
use AppBundle\Entity\Articles;
use AppBundle\Entity\Cart;
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
        $session_instance = new Session();
        if (!empty($session_instance->get('user')))
            return $this->redirectToRoute('user_auth');

        $conn = $this->getDoctrine()->getManager();
        $user = new Users();
        $form = $this->createForm(UsersType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $file = $form->get('photo')->getData();

            if(!empty($file)) {

                $slug_name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $slug_extension = $file->guessExtension();

                $file_name = sprintf(
                    "%s_%s.%s", $slug_name, uniqid(), $slug_extension
                );

                $user->setPhoto($file_name);

                $file->move($this->getParameter('uploadsUserDir'), $file_name);

            }

            $conn->persist($user);
            $conn->flush();

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

                if ($res->getRole() == 2) {
                    return $this->redirectToRoute('home');
                }

                $this->addFlash(
                    'info',
                    'Félicitations, vous êtes connecté !'
                );

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
     * @Route("/profile", name="user_profile")
     */
    public function profileAction(Request $request)
    {
        $session_instance = new Session();
        if (empty($session_instance->get('user')))
            return $this->redirectToRoute('user_auth');

        $conn = $this->getDoctrine()->getManager();
        $user = $conn->getRepository(Users::class)->find($session_instance->get('user')->getId());

        $form = $this->createForm(UsersType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            if(!empty($file)) {
                $file = $form->get('photo')->getData();

                $slug_name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $slug_extension = $file->guessExtension();

                $file_name = sprintf(
                    "%s_%s.%s", $slug_name, uniqid(), $slug_extension
                );

                $user->setPhoto($file_name);

                $file->move($this->getParameter('uploadsUserDir'), $file_name);
            }

            $conn->persist($user);
            $conn->flush();

            $this->addFlash(
                'info',
                'Profil modifié avec succès !'
            );

            return $this->redirectToRoute('user_profile');

        }

        if (!empty($session_instance->get('user'))) {
            $user_cart = $conn->getRepository(Cart::class)->findBy(['user' => $session_instance->get('user')->getId()]);
        }

        return $this->render('@App/Users/profile.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'dynamic' => 'Upload photo',
            'user_cart' => @$user_cart
        ]);
    }

    /**
     * @Route("/cart", name="user_cart")
     */
    public function cartAction(Request $request)
    {
        $session_instance = new Session();
        $conn = $this->getDoctrine()->getManager();

        if (empty($session_instance->get('user')))
            return $this->redirectToRoute('user_auth');

        if (!empty($session_instance->get('user'))) {

            $user_cart = $conn->getRepository(Cart::class)->findBy(['user' => $session_instance->get('user')->getId()]);

//            $total = 0;
//            foreach ($user_cart as $key) {
//                $t = $key->getArticle()->getPrice() * $key->getQuantityOrder();
//                $total += $t;
//            }

        }

        return $this->render('@App/Users/cart.html.twig', [
            'user_cart' => @$user_cart,
//            'total' => @$total
        ]);
    }

    /**
     * @Route("/delete/{id}", name="user_delete_cart")
     */
    public function deleteFromCartAction($id)
    {
        $conn = $this->getDoctrine()->getManager();
        $user_cart = $conn->getRepository(Cart::class)->find($id);

        $conn->remove($user_cart);
        $conn->flush();

        return $this->redirectToRoute('user_cart');
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
