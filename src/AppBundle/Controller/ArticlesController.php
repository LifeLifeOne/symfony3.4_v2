<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Articles;
use AppBundle\Form\ArticlesType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
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
        $conn = $this->getDoctrine()->getManager();

        if ($form->isSubmitted()) {

            $file = $form->get('photo')->getData();

            $slug_name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME); // Nom du fichier
            $slug_extension = $file->guessExtension(); // Nom de l'extension

            $file_name = sprintf(
              "%s_%s.%s", $slug_name, uniqid(), $slug_extension
            );

            $article->setPhoto($file_name);
            $file->move($this->getParameter('uploadsArticleDir'), $file_name);

            $conn->persist($article);
            $conn->flush();

            $this->addFlash(
                'info',
                'Article ajouté avec succès !'
            );

            return $this->redirectToRoute('articles_list');
        }

        return $this->render('@App/Articles/add.html.twig', [
            'form' => $form->createView(),
            'dynamic' => 'Ajouter'
        ]);
    }

    /**
     * @Route("/display", name="articles_list")
     */
    public function displayAction()
    {
        $session_instance = new Session();
        $session = $session_instance->get('user');
        if (empty($session))
            return $this->redirectToRoute('user_auth');

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

        $this->addFlash(
            'info',
            'Article supprimé avec succès !'
        );

        return $this->redirectToRoute('articles_list');
    }

    /**
     * @Route("/update/{id}", name="article_update")
     */
    public function modifierAction(Request $request, $id)
    {

        $conn = $this->getDoctrine()->getManager();
        $article = $conn->getRepository(Articles::class)->find($id);

        // On cree le formulaire
        $form = $this->createForm(ArticlesType::class,$article);
        $form->handleRequest($request);

        if($form->isSubmitted()) {

            $conn->persist($article);
            $conn->flush();

            $this->addFlash(
                'info',
                'Article modifié avec succès !'
            );

            return $this->redirectToRoute('articles_list');
        }

        return $this->render('@App/Articles/add.html.twig', array(
            'form'  => $form->createView(),
            'dynamic' => 'Modifier'
        ));
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

            $id = $request->get('select_list');
            $article = $conn->getRepository(Articles::class)->find($id);

            if ($request->get('btn') == "delete") {

                $conn->remove($article);
                $conn->flush();

                $this->addFlash(
                    'info',
                    'Suppression confirmée !'
                );

                return $this->redirectToRoute('exercice_select');
            }

            if ($request->get('btn') == "details") {

                return $this->render('@App/Articles/display_one.html.twig', [
                    "article" => $article
                ]);
            }

        }

        return $this->render('@App/Articles/exercice.html.twig', [
            "articles" => $articles
        ]);
    }

    /**
     * @route("/search", name="search")
     */
    public function searchAction(Request $request)
    {
        $search = $this->createForm(ArticlesType::class);
        $search->handleRequest($request);

        if ($search->isSubmitted()) {

            $form = $request->get('appbundle_articles');
            $name = $form['name'];
            $conn = $this->getDoctrine()->getManager();
            $find = $conn->getRepository(Articles::class)->findBy(['name' => $name]);
        }

        return $this->render('@App/Articles/search.html.twig', [
            "form" => $search->createView(),
            "find" => @$find
        ]);
    }

}
