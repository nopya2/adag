<?php

namespace App\Controller;

use App\Entity\Rubrique;
use App\Form\RubriqueType;
use App\Repository\RubriqueRepository;
use App\Entity\Commentaire;
use App\Form\CommentaireType;
use App\Repository\CommentaireRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Functions;

/**
 * @Route("/admin/rubrique")
 */
class RubriqueController extends AbstractController
{
    /**
     * @Route("/", name="admin_rubrique_index", methods="GET")
     */
    public function index(RubriqueRepository $rubriqueRepository, Request $request): Response
    {
        //Afficher ou Cacher un rubrique
        if($request->get('a') && $request->get('i')){
            $rubrique = $rubriqueRepository->find(intval($request->get('i')));
            if($request->get('a') === 'hide'){
                $rubrique->setVisible(false);
                $message = 'Votre rubrique est caché';
            }
            if($request->get('a') === 'show'){
                $rubrique->setVisible(true);
                $message = 'Votre rubrique est visible';
            }
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'modification',
                $message
            );

            return $this->redirectToRoute('admin_service_index');
        }

        return $this->render('backend/rubrique/index.html.twig', [
            'rubriques' => $rubriqueRepository->findAll(),
            'page' => "rubrique",
            'activePage' => "rubrique_liste"
        ]);
    }

    /**
     * @Route("/new", name="admin_rubrique_new", methods="GET|POST")
     */
    public function new(Request $request, Functions $fonctions): Response
    {
        $rubrique = new Rubrique();
        $form = $this->createForm(RubriqueType::class, $rubrique);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $rubrique->setSlug($fonctions->slugify($rubrique->getTitre()));
            $rubrique->setUser($this->getUser());
            $em = $this->getDoctrine()->getManager();
            $em->persist($rubrique);
            $em->flush();

            return $this->redirectToRoute('admin_rubrique_index');
        }

        return $this->render('backend/rubrique/new.html.twig', [
            'rubrique' => $rubrique,
            'form' => $form->createView(),
            'page' => "rubrique",
            'activePage' => "rubrique_nouveau"
        ]);
    }

    /**
     * @Route("/{id}", name="rubrique_show", methods="GET")
     */
    public function show(Rubrique $rubrique): Response
    {
        return $this->render('backend/rubrique/show.html.twig', ['rubrique' => $rubrique]);
    }

    /**
     * @Route("/{id}/edit", name="admin_rubrique_edit", methods="GET|POST")
     */
    public function edit(Request $request, Rubrique $rubrique, Functions $fonctions): Response
    {
        $pan = 'formulaire';

        if($request->query->get('pan'))
            $pan = $request->query->get('pan');

        $form = $this->createForm(RubriqueType::class, $rubrique);
        $form->handleRequest($request);

        $commentaire = new Commentaire();
        $formCommentaire = $this->createForm(CommentaireType::class, $commentaire)
            ->remove('nom')
            ->remove('email')
            ->remove('website');
        $formCommentaire->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $rubrique->setSlug($fonctions->slugify($rubrique->getTitre()));
            $rubrique->setUpdatedAt(new \DateTime());
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'succes',
                'Vos modifications ont été enregistrées!'
            );

            return $this->redirectToRoute('admin_rubrique_edit', ['id' => $rubrique->getId()]);
        }

        if ($formCommentaire->isSubmitted() && $formCommentaire->isValid()) {
            $commentaire->setNom('NEOSYS TECHNOLOGIE SUPPORT');
            $commentaire->setEmail('infos@neosystechnologie.ga');
            $commentaire->setWebsite('neosystechnologie.ga');
            $commentaire->setType('interne');
            $commentaire->setRubrique($rubrique);
            $commentaire->setSendedAt(new \DateTime());
            $em = $this->getDoctrine()->getManager();
            $em->persist($commentaire);
            $em->flush();

            return $this->redirectToRoute('admin_rubrique_edit', ['id' => $rubrique->getId(), 'pan' => $pan]);
        }

        return $this->render('backend/rubrique/edit.html.twig', [
            'rubrique' => $rubrique,
            'form' => $form->createView(),
            'commentaire' => $commentaire,
            'formCommentaire' => $formCommentaire->createView(),
            'pan' => $pan,
            'page' => "rubrique",
            'activePage' => "rubrique_modifier"
        ]);
    }

    /**
     * @Route("/{id}", name="admin_rubrique_delete", methods="DELETE")
     */
    public function delete(Request $request, Rubrique $rubrique): Response
    {
        if ($this->isCsrfTokenValid('delete'.$rubrique->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($rubrique);
            $em->flush();
        }

        return $this->redirectToRoute('admin_rubrique_index');
    }
}
