<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Form\EvenementType;
use App\Repository\EvenementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/admin/evenement")
 */
class EvenementController extends AbstractController
{
    /**
     * @Route("/", name="admin_evenement_index", methods="GET")
     */
    public function index(EvenementRepository $evenementRepository): Response
    {
        $evenement = new Evenement();
        $form = $this->createForm(EvenementType::class, $evenement);

        return $this->render('backend/evenement/index.html.twig', [
            'page' => "evenement",
            'activePage' => "evenement_liste",
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/new", name="admin_evenement_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $evenement = new Evenement();
        $evenement->setCreatedBy($this->getUser());

        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($evenement);
            $em->flush();

            $this->addFlash(
                'succes',
                'Votre évènement a été créé avec succès!'
            );

            return $this->redirectToRoute('admin_evenement_index');
        }

        return $this->render('backend/evenement/new.html.twig', [
            'evenement' => $evenement,
            'form' => $form->createView(),
            'page' => "evenement",
            'activePage' => "evenement_nouveau",
        ]);
    }

    /**
     * @Route("/modal/new", name="admin_evenement_modal_new", methods="GET|POST")
     */
    public function newModal(Request $request): Response
    {
        $evenement = new Evenement();
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($evenement);
            $em->flush();

            return $this->redirectToRoute('evenement_index');
        }

        return $this->render('evenement/modal/new.html.twig', [
            'evenement' => $evenement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_evenement_show", methods="GET")
     */
    public function show(Evenement $evenement): Response
    {
        return $this->render('evenement/show.html.twig', ['evenement' => $evenement]);
    }

    /**
     * @Route("/{id}/edit", name="admin_evenement_edit", methods="GET|POST")
     */
    public function edit(Request $request, Evenement $evenement): Response
    {
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'succes',
                'Vos modifications ont été enregistrées!'
            );

            return $this->redirectToRoute('admin_evenement_edit', ['id' => $evenement->getId()]);
        }

        return $this->render('backend/evenement/edit.html.twig', [
            'evenement' => $evenement,
            'form' => $form->createView(),
            'page' => "evenement",
            'activePage' => "evenement_creer",
        ]);
    }

    /**
     * @Route("/{id}", name="admin_evenement_delete", methods="DELETE")
     */
    public function delete(Request $request, Evenement $evenement): Response
    {
        if ($this->isCsrfTokenValid('delete'.$evenement->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($evenement);
            $em->flush();
        }

        return $this->redirectToRoute('evenement_index');
    }

    /**
     * @Route("/json/evenements", name="evenements_json", methods="GET")
     */
    public function getAllEvenements(EvenementRepository $evenementRepository): Response
    {
        $evenements = $evenementRepository->findAll();
        return new JsonResponse(array('evenements'=>$evenements));
    }
}
