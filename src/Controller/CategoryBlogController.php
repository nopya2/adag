<?php

namespace App\Controller;

use App\Entity\CategoryBlog;
use App\Form\CategoryBlogType;
use App\Repository\CategoryBlogRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/category/blog")
 */
class CategoryBlogController extends AbstractController
{
    /**
     * @Route("/", name="category_blog_index", methods="GET")
     */
    public function index(CategoryBlogRepository $categoryBlogRepository): Response
    {
        return $this->render('category_blog/index.html.twig', ['category_blogs' => $categoryBlogRepository->findAll()]);
    }

    /**
     * @Route("/new", name="category_blog_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $categoryBlog = new CategoryBlog();
        $form = $this->createForm(CategoryBlogType::class, $categoryBlog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($categoryBlog);
            $em->flush();

            return $this->redirectToRoute('category_blog_index');
        }

        return $this->render('category_blog/new.html.twig', [
            'category_blog' => $categoryBlog,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="category_blog_show", methods="GET")
     */
    public function show(CategoryBlog $categoryBlog): Response
    {
        return $this->render('category_blog/show.html.twig', ['category_blog' => $categoryBlog]);
    }

    /**
     * @Route("/{id}/edit", name="category_blog_edit", methods="GET|POST")
     */
    public function edit(Request $request, CategoryBlog $categoryBlog): Response
    {
        $form = $this->createForm(CategoryBlogType::class, $categoryBlog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('category_blog_edit', ['id' => $categoryBlog->getId()]);
        }

        return $this->render('category_blog/edit.html.twig', [
            'category_blog' => $categoryBlog,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="category_blog_delete", methods="DELETE")
     */
    public function delete(Request $request, CategoryBlog $categoryBlog): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categoryBlog->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($categoryBlog);
            $em->flush();
        }

        return $this->redirectToRoute('category_blog_index');
    }
}
