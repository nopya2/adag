<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Form\BlogType;
use App\Repository\BlogRepository;
use App\Entity\Commentaire;
use App\Form\CommentaireType;
use App\Repository\CommentaireRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/blog")
 */
class BlogController extends AbstractController
{
    /**
     * @Route("/", name="admin_blog_index", methods="GET")
     */
    public function index(BlogRepository $blogRepository, Request $request): Response
    {
        //Afficher ou Cacher un blog
        if($request->get('a') && $request->get('i')){
            $blog = $blogRepository->find(intval($request->get('i')));
            if($request->get('a') === 'hide'){
                $blog->setVisible(false);
                $message = 'Votre blog est caché';
            }
            if($request->get('a') === 'show'){
                $blog->setVisible(true);
                $message = 'Votre blog est visible';
            }
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'modification',
                $message
            );

            return $this->redirectToRoute('admin_blog_index');
        }

        return $this->render('backend/blog/index.html.twig', [
            'blogs' => $blogRepository->findAll(),
            'page' => "blog",
            'activePage' => "blog_liste"
        ]);
    }

    /**
     * @Route("/new", name="admin_blog_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $blog = new Blog();
        $form = $this->createForm(BlogType::class, $blog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $blog->setCreatedBy($this->getUser());
            $em = $this->getDoctrine()->getManager();
            $em->persist($blog);
            $em->flush();

            return $this->redirectToRoute('admin_blog_index');
        }

        return $this->render('backend/blog/new.html.twig', [
            'blog' => $blog,
            'form' => $form->createView(),
            'page' => "blog",
            'activePage' => "blog_nouveau"
        ]);
    }

    /**
     * @Route("/{id}", name="blog_show", methods="GET")
     */
    public function show(Blog $blog): Response
    {
        return $this->render('backend/blog/show.html.twig', ['blog' => $blog]);
    }

    /**
     * @Route("/{id}/edit", name="admin_blog_edit", methods="GET|POST")
     */
    public function edit(Request $request, Blog $blog): Response
    {
        $pan = 'formulaire';

        if($request->query->get('pan'))
            $pan = $request->query->get('pan');

        $form = $this->createForm(BlogType::class, $blog);
        $form->handleRequest($request);

        $commentaire = new Commentaire();
        $formCommentaire = $this->createForm(CommentaireType::class, $commentaire)
            ->remove('nom')
            ->remove('email')
            ->remove('website');
        $formCommentaire->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $blog->setUpdatedAt(new \DateTime());
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'succes',
                'Vos modifications ont été enregistrées!'
            );

            return $this->redirectToRoute('admin_blog_edit', ['id' => $blog->getId()]);
        }

        if ($formCommentaire->isSubmitted() && $formCommentaire->isValid()) {
            $commentaire->setNom('NEOSYS TECHNOLOGIE SUPPORT');
            $commentaire->setEmail('infos@neosystechnologie.ga');
            $commentaire->setWebsite('neosystechnologie.ga');
            $commentaire->setType('interne');
            $commentaire->setBlog($blog);
            $commentaire->setSendedAt(new \DateTime());
            $em = $this->getDoctrine()->getManager();
            $em->persist($commentaire);
            $em->flush();

            return $this->redirectToRoute('admin_blog_edit', ['id' => $blog->getId(), 'pan' => $pan]);
        }

        return $this->render('backend/blog/edit.html.twig', [
            'blog' => $blog,
            'form' => $form->createView(),
            'commentaire' => $commentaire,
            'formCommentaire' => $formCommentaire->createView(),
            'pan' => $pan,
            'page' => "blog",
            'activePage' => "blog_modifier"
        ]);
    }

    /**
     * @Route("/{id}", name="admin_blog_delete", methods="DELETE")
     */
    public function delete(Request $request, Blog $blog): Response
    {
        if ($this->isCsrfTokenValid('delete'.$blog->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($blog);
            $em->flush();
        }

        return $this->redirectToRoute('admin_blog_index');
    }
}
