<?php

namespace App\Controller;

use App\Entity\Service;
use App\Form\ServiceType;
use App\Repository\ServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/service")
 */
class ServiceController extends AbstractController
{
    /**
     * @Route("/", name="admin_service_index", methods="GET")
     */
    public function index(ServiceRepository $serviceRepository, Request $request): Response
    {
        //Afficher ou Cacher un service
        if($request->get('a') && $request->get('i')){
            $service = $serviceRepository->find(intval($request->get('i')));
            if($request->get('a') === 'hide'){
                $service->setVisible(false);
                $message = 'Votre service est caché';
            }
            if($request->get('a') === 'show'){
                $service->setVisible(true);
                $message = 'Votre service est visible';
            }
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'modification',
                $message
            );

            return $this->redirectToRoute('admin_service_index');
        }

        return $this->render('backend/service/index.html.twig', [
            'services' => $serviceRepository->findAll(),
            'page' => "service",
            'activePage' => "service_liste"
        ]);
    }

    /**
     * @Route("/new", name="admin_service_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {

        $service = new Service();
        $service->setUser($this->getUser());
        $form = $this->createForm(ServiceType::class, $service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($service);
            $em->flush();

            $this->addFlash(
                'succes',
                'Votre service a été créé avec succès!'
            );

            return $this->redirectToRoute('admin_service_index');
        }

        return $this->render('backend/service/new.html.twig', [
            'service' => $service,
            'form' => $form->createView(),
            'page' => "service",
            'activePage' => "service_nouveau"
        ]);
    }

    /**
     * @Route("/{id}", name="admin_service_show", methods="GET")
     */
    public function show(Service $service): Response
    {
        return $this->render('backend/service/show.html.twig', ['service' => $service]);
    }

    /**
     * @Route("/{id}/edit", name="admin_service_edit", methods="GET|POST")
     */
    public function edit(Request $request, Service $service): Response
    {
        $page = "service";
        $activePacte = "modifier";

        $form = $this->createForm(ServiceType::class, $service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $service->setUpdatedAt(new \DateTime());
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'succes',
                'Vos modifications ont été enregistrées!'
            );

            return $this->redirectToRoute('admin_service_edit', ['id' => $service->getId()]);
        }

        return $this->render('backend/service/edit.html.twig', [
            'service' => $service,
            'form' => $form->createView(),
            'page' => "service",
            'activePage' => "service_modifier"
        ]);
    }

    /**
     * @Route("/{id}", name="admin_service_delete", methods="DELETE")
     */
    public function delete(Request $request, Service $service): Response
    {
        if ($this->isCsrfTokenValid('delete'.$service->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($service);
            $em->flush();

            $this->addFlash(
                'suppression',
                'Votre service a été supprimé!'
            );
        }

        return $this->redirectToRoute('admin_service_index');
    }
}
