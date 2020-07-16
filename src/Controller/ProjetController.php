<?php

namespace App\Controller;

use App\Entity\Projet;
use App\Form\ProjetType;
use App\Repository\ProjetRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/projet")
 */
class ProjetController extends AbstractController
{
    /**
     * @Route("/", name="admin_projet_index", methods="GET")
     */
    public function index(ProjetRepository $projetRepository, Request $request): Response
    {
        //Afficher ou Cacher un projet
        if($request->get('a') && $request->get('i')){
            $projet = $projetRepository->find(intval($request->get('i')));
            if($request->get('a') === 'hide'){
                $projet->setVisible(false);
                $message = 'Votre projet est caché';
            }
            if($request->get('a') === 'show'){
                $projet->setVisible(true);
                $message = 'Votre projet est visible';
            }
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'modification',
                $message
            );

            return $this->redirectToRoute('admin_projet_index');
        }

        return $this->render('backend/projet/index.html.twig', [
            'projets' => $projetRepository->findAll(),
            'page' => "projet",
            'activePage' => "projet_liste"
        ]);
    }

    /**
     * @Route("/new", name="admin_projet_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $projet = new Projet();
        $projet->setCreatedBy($this->getUser());
        $form = $this->createForm(ProjetType::class, $projet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($projet);
            $em->flush();

            $this->addFlash(
                'succes',
                'Votre projet a été créé avec succès!'
            );

            return $this->redirectToRoute('admin_projet_index');
        }

        return $this->render('backend/projet/new.html.twig', [
            'projet' => $projet,
            'form' => $form->createView(),
            'page' => "projet",
            'activePage' => "projet_nouveau"
        ]);
    }

    /**
     * @Route("/{id}", name="admin_projet_show", methods="GET")
     */
    public function show(Projet $projet): Response
    {
        return $this->render('backend/projet/show.html.twig', ['projet' => $projet]);
    }

    /**
     * @Route("/{id}/edit", name="admin_projet_edit", methods="GET|POST")
     */
    public function edit(Request $request, Projet $projet): Response
    {
        $form = $this->createForm(ProjetType::class, $projet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'succes',
                'Vos modifications ont été enregistrées!'
            );

            return $this->redirectToRoute('admin_projet_edit', ['id' => $projet->getId()]);
        }

        return $this->render('backend/projet/edit.html.twig', [
            'projet' => $projet,
            'form' => $form->createView(),
            'page' => "projet",
            'activePage' => "projet_modifier"
        ]);
    }

    /**
     * @Route("/{id}", name="admin_projet_delete", methods="DELETE")
     */
    public function delete(Request $request, Projet $projet): Response
    {
        if ($this->isCsrfTokenValid('delete'.$projet->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($projet);
            $em->flush();

            $this->addFlash(
                'suppression',
                'Votre service a ete supprime!'
            );
        }

        return $this->redirectToRoute('admin_projet_index');
    }
}
