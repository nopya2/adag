<?php

namespace App\Controller;

use App\Repository\CommentaireArticleRepository;
use App\Entity\CommentaireArticle;
use App\Form\CommentaireArticleType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/commentaire")
 */
class CommentaireArticleController extends AbstractController
{
    /**
     * @Route("/", name="admin_commentaire_index", methods="GET")
     */
    public function index(CommentaireArticleRepository $commentaireRepository, Request $request): Response
    {

        return $this->render('backend/commentaire/index.html.twig', [
            'commentaires' => $commentaireRepository->findAll(),
            'page' => "article",
            'activePage' => "commentaire_liste"
        ]);
    }

    // /**
    //  * @Route("/new", name="admin_article_new", methods="GET|POST")
    //  */
    // public function new(Request $request): Response
    // {
    //     $article = new Article();
    //     $form = $this->createForm(ArticleType::class, $article);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $article->setAuteur($this->getUser());
    //         $em = $this->getDoctrine()->getManager();
    //         $em->persist($article);
    //         $em->flush();

    //         return $this->redirectToRoute('admin_article_index');
    //     }

    //     return $this->render('backend/article/new.html.twig', [
    //         'article' => $article,
    //         'form' => $form->createView(),
    //         'page' => "article",
    //         'activePage' => "article_nouveau"
    //     ]);
    // }

    // /**
    //  * @Route("/{id}", name="article_show", methods="GET")
    //  */
    // public function show(Article $article): Response
    // {
    //     return $this->render('backend/article/show.html.twig', ['article' => $article]);
    // }

    // /**
    //  * @Route("/{id}/edit", name="admin_article_edit", methods="GET|POST")
    //  */
    // public function edit(Request $request, Article $article): Response
    // {
    //     $pan = 'formulaire';

    //     if($request->query->get('pan'))
    //         $pan = $request->query->get('pan');

    //     $form = $this->createForm(ArticleType::class, $article);
    //     $form->handleRequest($request);

    //     $commentaire = new CommentaireArticle();
    //     $formCommentaire = $this->createForm(CommentaireArticleType::class, $commentaire)
    //         ->remove('nom')
    //         ->remove('email')
    //         ->remove('siteweb');
    //     $formCommentaire->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         // $article->setUpdatedAt(new \DateTime());
    //         $this->getDoctrine()->getManager()->flush();

    //         $this->addFlash(
    //             'succes',
    //             'Vos modifications ont été enregistrées!'
    //         );

    //         return $this->redirectToRoute('admin_article_edit', ['id' => $article->getId()]);
    //     }

    //     if ($formCommentaire->isSubmitted() && $formCommentaire->isValid()) {
    //         $commentaire->setNom('NEOSYS TECHNOLOGIE SUPPORT');
    //         $commentaire->setEmail('infos@neosystechnologie.ga');
    //         $commentaire->setSiteWeb('neosystechnologie.ga');
    //         $commentaire->setType('interne');
    //         $commentaire->setArticle($article);
    //         $commentaire->setCreatedAt(new \DateTime());
    //         $em = $this->getDoctrine()->getManager();
    //         $em->persist($commentaire);
    //         $em->flush();

    //         return $this->redirectToRoute('admin_article_edit', ['id' => $article->getId(), 'pan' => $pan]);
    //     }

    //     return $this->render('backend/article/edit.html.twig', [
    //         'article' => $article,
    //         'form' => $form->createView(),
    //         'commentaire' => $commentaire,
    //         'formCommentaire' => $formCommentaire->createView(),
    //         'pan' => $pan,
    //         'page' => "article",
    //         'activePage' => "article_modifier"
    //     ]);
    // }

    /**
     * @Route("/{id}", name="admin_commentaire_delete", methods="DELETE")
     */
    public function delete(Request $request, CommentaireArticle $commentaire): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commentaire->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($commentaire);
            $em->flush();
        }

        return $this->redirectToRoute('admin_commentaire_index');
    }
}
