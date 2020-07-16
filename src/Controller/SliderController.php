<?php

namespace App\Controller;

use App\Entity\Slider;
use App\Form\SliderType;
use App\Repository\SliderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/slider")
 */
class SliderController extends AbstractController
{
    /**
     * @Route("/", name="admin_slider_index", methods="GET")
     */
    public function index(SliderRepository $sliderRepository, Request $request): Response
    {
        if($request->get('a') && $request->get('i')){
            $slider = $sliderRepository->find(intval($request->get('i')));
            if($request->get('a') === 'hide'){
                $slider->setVisible(false);
            }
            if($request->get('a') === 'show'){
                $slider->setVisible(true);
            }
            $this->getDoctrine()->getManager()->flush();
        }
        
        return $this->render('backend/slider/index.html.twig', [
            'sliders' => $sliderRepository->findAll(),
            'page' => "slider",
            'activePage' => "slider_liste"
        ]);
    }

    /**
     * @Route("/new", name="admin_slider_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $slider = new Slider();
        $form = $this->createForm(SliderType::class, $slider);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($slider);
            $em->flush();

            $this->addFlash(
                'succes',
                'Votre slider a ete cree avec succes!'
            );

            return $this->redirectToRoute('admin_slider_index');
        }

        return $this->render('backend/slider/new.html.twig', [
            'slider' => $slider,
            'form' => $form->createView(),
            'page' => "slider",
            'activePage' => "slider_nouveau"
        ]);
    }

    /**
     * @Route("/{id}", name="admin_slider_show", methods="GET")
     */
    public function show(Slider $slider): Response
    {
        return $this->render('slider/show.html.twig', ['slider' => $slider]);
    }

    /**
     * @Route("/{id}/edit", name="admin_slider_edit", methods="GET|POST")
     */
    public function edit(Request $request, Slider $slider): Response
    {
        $form = $this->createForm(SliderType::class, $slider);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'succes',
                'Vos modifications ont été enregistrées!'
            );

            return $this->redirectToRoute('admin_slider_edit', ['id' => $slider->getId()]);
        }

        return $this->render('backend/slider/edit.html.twig', [
            'slider' => $slider,
            'form' => $form->createView(),
            'page' => "slider",
            'activePage' => "slider_modifier"
        ]);
    }

    /**
     * @Route("/{id}", name="admin_slider_delete", methods="DELETE")
     */
    public function delete(Request $request, Slider $slider): Response
    {
        if ($this->isCsrfTokenValid('delete'.$slider->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($slider);
            $em->flush();

            $this->addFlash(
                'suppression',
                'Votre slider a ete supprime!'
            );
        }

        return $this->redirectToRoute('admin_slider_index');
    }
}
