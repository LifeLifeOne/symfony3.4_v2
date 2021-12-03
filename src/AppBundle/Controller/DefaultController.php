<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Articles;
use AppBundle\Entity\Cart;
use AppBundle\Entity\Users;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function indexAction(Request $request)
    {
        $session_instance = new Session();
        $cart_instance = new Cart();
        $conn = $this->getDoctrine()->getManager();
        $articles = $conn->getRepository(Articles::class)->findAll();

        if ($request->isMethod('POST')) {


            $user = $session_instance->get('user');
            $id_article_hidden = $request->get('id_article_hidden');

            $article = $conn->getRepository(Articles::class)->find($id_article_hidden);
            $user_connected = $conn->getRepository(Users::class)->find($user);

            $price = $article->getPrice();
            $total = $price * $request->get('quantity');

            $cart_instance->setUser($user_connected);
            $cart_instance->setArticle($article);
            $cart_instance->setTotalOrder($total);
            $cart_instance->setDateOrder(date('Y-m-d'));
            $cart_instance->setQuantityOrder($request->get('quantity'));
            $conn->persist($cart_instance);
            $conn->flush();

            $this->addFlash(
                'info',
                'Article ajouté avec succès !'
            );

            return $this->redirectToRoute('home');

        }

        if (!empty($session_instance->get('user'))) {
            $user_cart = $conn->getRepository(Cart::class)->findBy(['user' => $session_instance->get('user')->getId()]);
        }

        return $this->render('@App/Default/home.html.twig', [
            'articles' => $articles,
            'user_cart' => @$user_cart
        ]);
    }

}
