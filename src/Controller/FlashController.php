<?php

namespace App\Controller;

use App\Entity\Flash;
use App\Form\FlashType;
use App\Repository\FlashRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/admin/flash")
 */
class FlashController extends AbstractController
{
    /**
     * @Route("/", name="admin_flash_index", methods="GET")
     */
    public function index(FlashRepository $flashRepository, Request $request): Response
    {

        return $this->render('backend/flash/index.html.twig', [
            'flashs' => $flashRepository->findAll(),
            'page' => "flash",
            'activePage' => "flash_liste"
        ]);
    }

    /**
     * @Route("/new", name="admin_flash_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $flash = new Flash();
        $form = $this->createForm(FlashType::class, $flash);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $flash->setAuteur($this->getUser());
            $em = $this->getDoctrine()->getManager();
            $em->persist($flash);
            $em->flush();

            return $this->redirectToRoute('admin_flash_index');
        }

        return $this->render('backend/flash/new.html.twig', [
            'flash' => $flash,
            'form' => $form->createView(),
            'page' => "flash",
            'activePage' => "flash_nouveau"
        ]);
    }

    // /**
    //  * @Route("/{id}", name="flash_show", methods="GET")
    //  */
    // public function show(Flash $flash): Response
    // {
    //     return $this->render('backend/flash/show.html.twig', ['flash' => $flash]);
    // }

    /**
     * @Route("/{id}/edit", name="admin_flash_edit", methods="GET|POST|DELETE")
     */
    public function edit(Request $request, Flash $flash): Response
    {
        $form = $this->createForm(FlashType::class, $flash);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $flash->setUpdatedAt(new \DateTime());
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'succes',
                'Vos modifications ont été enregistrées!'
            );

            return $this->redirectToRoute('admin_flash_edit', ['id' => $flash->getId()]);
        }

        return $this->render('backend/flash/edit.html.twig', [
            'flash' => $flash,
            'form' => $form->createView(),
            'page' => "flash",
            'activePage' => "flash_modifier"
        ]);
    }

    /**
     * @Route("/{id}", name="admin_flash_delete", methods="DELETE")
     */
    public function delete(Request $request, Flash $flash): Response
    {
        if ($this->isCsrfTokenValid('delete'.$flash->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($flash);
            $em->flush();
        }

        return $this->redirectToRoute('admin_flash_index');
    }
}
