<?php

namespace App\Controller\Rest;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\View;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Evenement;
use App\Form\EvenementType;
use App\Repository\EvenementRepository;

class EvenementController extends FOSRestController
{

    /**
    * Retrieves all evenements resource
    * @Rest\Get("/evenements")
    * @Rest\View
    */
    public function getEvenements(EvenementRepository $evenementRepository)
    {
        $evenements = $evenementRepository->findAll();
        
        return $evenements;
    }

    /**
    * Retrieves an evenement resource
    * @Rest\View()
    * @Rest\Get("/evenements/{id}")
    */
    public function getEvenement(Evenement $evenement)
    {
        // In case our GET was a success we need to return a 200 HTTP OK response with the request object
        // $view = View::create($evenement);
        // $view->setFormat('json');

        return $evenement;
    }

    /**
    * Retrieves an evenement resource
    * @Rest\View()
    * @Rest\Post("/evenements/new")
    */
    public function new(Request $request)
    {
        $evenement = new Evenement();
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $evenement->setCreatedBy($this->getUser());
            $em = $this->getDoctrine()->getManager();
            $em->persist($evenement);
            $em->flush();

            return ['evenement' => $evenement];
        }else{
            return ['form' => $form];
        }
    }

    /**
    * Retrieves an evenement resource
    * @Rest\View()
    * @Rest\Delete("/evenements/delete/{id}")
    */
    public function delete(Request $request, Evenement $evenement)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($evenement);
        $em->flush();
        
        return ['success' => 1];
    }

    // /**
    //  * @Route("/{id}/edit", name="admin_evenement_edit", methods="GET|POST")
    //  */
    // public function edit(Request $request, Evenement $evenement): Response
    // {
    //     $form = $this->createForm(EvenementType::class, $evenement);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $this->getDoctrine()->getManager()->flush();

    //         return $this->redirectToRoute('evenement_edit', ['id' => $evenement->getId()]);
    //     }

    //     return $this->render('evenement/edit.html.twig', [
    //         'evenement' => $evenement,
    //         'form' => $form->createView(),
    //     ]);
    // }
}
