<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Articles;
use AppBundle\Form\ArticlesType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/articles")
 */
class ArticlesController extends Controller
{
    /**
     * @Route("/add", name="article_add")
     */
    public function addAction(Request $request)
    {
        $article = new Articles();
        $form = $this->createForm(ArticlesType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            // Connection
            $conn = $this->getDoctrine()->getManager();

            // Persist vers la table
            $conn->persist($article);

            // Equivalent de execute() de PDO
            $conn->flush();

            $this->addFlash(
                'info',
                'Article ajouté avec succès !'
            );

            return $this->redirectToRoute('articles_list');
        }

        return $this->render('@App/Articles/add.html.twig', [
            "frm" => $form->createView(),
        ]);
    }

    /**
     * @Route("/display", name="articles_list")
     */
    public function displayAction()
    {
        $conn = $this->getDoctrine()->getManager();
        $articles = $conn->getRepository(Articles::class)->findAll();

        return $this->render('@App/Articles/display.html.twig', [
            "articles" => $articles
        ]);
    }

    /**
     * @Route("/display/{id}", name="article_details")
     */
    public function displayOneAction($id)
    {
        $conn = $this->getDoctrine()->getManager();
        $article = $conn->getRepository(Articles::class)->find($id);

        return $this->render('@App/Articles/display_one.html.twig', [
            "article" => $article
        ]);
    }

    /**
     * @Route("/delete/{id}", name="article_delete")
     */
    public function deleteAction($id)
    {
        $conn = $this->getDoctrine()->getManager();
        $article = $conn->getRepository(Articles::class)->find($id);
        $conn->remove($article);
        $conn->flush();

        return $this->redirectToRoute('articles_list');
    }

    /**
     * @Route("/exercice", name="exercice_select")
     * @return Response|null
     */
    public function exerciceAction(Request $request)
    {
        $conn = $this->getDoctrine()->getManager();
        $articles = $conn->getRepository(Articles::class)->findAll();

        if ($request->isMethod('POST')) {

            if ($request->get('btn') == "delete") {

                $id = $_POST['select_list'];
                $article = $conn->getRepository(Articles::class)->find($id);
                $conn->remove($article);
                $conn->flush();

                return $this->redirectToRoute('exercice_select');
            }

            if ($request->get('btn') == "details") {

                $id = $_POST['select_list'];
                $article = $conn->getRepository(Articles::class)->find($id);

                return $this->render('@App/Articles/display_one.html.twig', [
                    "article" => $article
                ]);
            }

        }

        return $this->render('@App/Articles/exercice.html.twig', [
            "articles" => $articles
        ]);
    }

}
