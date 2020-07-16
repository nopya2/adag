<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use App\Entity\CommentaireArticle;
use App\Form\CommentaireArticleType;
use App\Repository\CommentaireArticleRepository;
use App\Repository\ConfigurationRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Functions;

/**
 * @Route("/admin/article")
 */
class ArticleController extends AbstractController
{
    /**
     * @Route("/", name="admin_article_index", methods="GET")
     */
    public function index(ArticleRepository $articleRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $search = '';
        if($request->query->get('search'))
            $search = $request->query->get('search');
        $qArticles = $articleRepository->findArticles($search);
        $articles = $paginator->paginate(
            $qArticles, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            15 /*limit per page*/
        );

        return $this->render('backend/article/index.html.twig', [
            'articles' => $articles,
            'search' => $search,
            'page' => "article",
            'activePage' => "article_liste"
        ]);
    }

    /**
     * @Route("/new", name="admin_article_new", methods="GET|POST")
     */
    public function new(Request $request, Functions $fonctions): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article->setSlug($fonctions->slugify($article->getTitre()));
            $article->setAuteur($this->getUser());
            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();

            return $this->redirectToRoute('admin_article_index');
        }

        return $this->render('backend/article/new.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
            'page' => "article",
            'activePage' => "article_nouveau"
        ]);
    }

    /**
     * @Route("/{id}", name="article_show", methods="GET")
     */
    public function show(Article $article): Response
    {
        return $this->render('backend/article/show.html.twig', ['article' => $article]);
    }

    /**
     * @Route("/{id}/edit", name="admin_article_edit", methods="GET|POST|DELETE")
     */
    public function edit(Request $request, Article $article, CommentaireArticleRepository $commentaireRepos,
        ConfigurationRepository $configurationRepos, Functions $fonctions): Response
    {
        $pan = 'formulaire';

        if($request->query->get('pan'))
            $pan = $request->query->get('pan');

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        $commentaire = new CommentaireArticle();
        $formCommentaire = $this->createForm(CommentaireArticleType::class, $commentaire)
            ->remove('nom')
            ->remove('email')
            ->remove('siteweb');
        $formCommentaire->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article->setSlug($fonctions->slugify($article->getTitre()));
            // $article->setUpdatedAt(new \DateTime());
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'succes',
                'Vos modifications ont été enregistrées!'
            );

            return $this->redirectToRoute('admin_article_edit', ['id' => $article->getId()]);
        }

        if ($formCommentaire->isSubmitted() && $formCommentaire->isValid()) {
            $configuration = $configurationRepos->find(1);
            $commentaire->setNom($configuration->getNomSite());
            $commentaire->setEmail($configuration->getEmail());
            $commentaire->setSiteWeb($configuration->getSiteweb());
            $commentaire->setType('interne');
            $commentaire->setArticle($article);
            $commentaire->setCreatedAt(new \DateTime());
            $em = $this->getDoctrine()->getManager();
            $em->persist($commentaire);
            $em->flush();

            return $this->redirectToRoute('admin_article_edit', ['id' => $article->getId(), 'pan' => $pan]);
        }

        if($request->get('_method') && $request->get('_method') === "DELETE"){
            $em = $this->getDoctrine()->getManager();
            $commentaire = $commentaireRepos->find($request->get('_commentaire'));
            $em->remove($commentaire);
            $em->flush();

            return $this->redirectToRoute('admin_article_edit', ['id' => $article->getId(), 'pan' => $pan]);
        }

        return $this->render('backend/article/edit.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
            'commentaire' => $commentaire,
            'formCommentaire' => $formCommentaire->createView(),
            'pan' => $pan,
            'page' => "article",
            'activePage' => "article_modifier"
        ]);
    }

    /**
     * @Route("/{id}", name="admin_article_delete", methods="DELETE")
     */
    public function delete(Article $article, Request $request): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($article);
            $em->flush();
        }

        $this->addFlash(
            'succes',
            'Suppression! Cet aricle a été supprimé.'
        );

        return $this->redirectToRoute('admin_article_index');
    }
}
