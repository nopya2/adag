<?php

namespace App\Controller;

use App\Entity\Partenaire;
use App\Form\PartenaireType;
use App\Repository\PartenaireRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Service\Functions;

/**
 * @Route("/admin/partenaire")
 */
class PartenaireController extends Controller
{
    /**
     * @Route("/", name="admin_partenaire_index", methods="GET")
     */
    public function index(PartenaireRepository $partenaireRepository, Request $request): Response
    {
        return $this->render('backend/partenaire/index.html.twig', [
            'partenaires' => $partenaireRepository->findAll(),
            'page' => "partenaire",
            'activePage' => "partenaire_liste",
        ]);
    }

    /**
     * @Route("/new", name="admin_partenaire_new", methods="GET|POST")
     */
    public function new(Request $request, Functions $fonction): Response
    {
        $partenaire = new Partenaire();
        $form = $this->createForm(PartenaireType::class, $partenaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($partenaire);
            $em->flush();

            $this->addFlash(
                'succes',
                'Votre partenaire a été ajouté avec succès!'
            );

            return $this->redirectToRoute('admin_partenaire_index');
        }

        return $this->render('backend/partenaire/new.html.twig', [
            'partenaire' => $partenaire,
            'form' => $form->createView(),
            'page' => "partenaire",
            'activePage' => "partenaire_creer"
        ]);
    }

    /**
     * @Route("/{id}", name="admin_partenaire_show", methods="GET")
     */
    public function show(Partenaire $partenaire): Response
    {
        return $this->render('backend/partenaire/show.html.twig', [
            'partenaire' => $partenaire,
            'page' => "partenaire",
            'activePage' => "partenaire_detail"
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_partenaire_edit", methods="GET|POST")
     */
    public function edit(Request $request, Partenaire $partenaire): Response
    {
        $form = $this->createForm(PartenaireType::class, $partenaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'succes',
                'Vos modifications ont été enregistrées!'
            );

            return $this->redirectToRoute('admin_partenaire_edit', ['id' => $partenaire->getId()]);
        }

        return $this->render('backend/partenaire/edit.html.twig', [
            'partenaire' => $partenaire,
            'form' => $form->createView(),
            'page' => "partenaire",
            'activePage' => "partenaire_modifier"
        ]);
    }

    /**
     * @Route("/{id}", name="admin_partenaire_delete", methods="DELETE")
     */
    public function delete(Request $request, Partenaire $partenaire): Response
    {
        if ($this->isCsrfTokenValid('delete'.$partenaire->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($partenaire);
            $em->flush();
        }

        return $this->redirectToRoute('admin_partenaire_index');
    }

    public function setFirstItemIndex($page, $limit){
        if(!isset($page) || $page == 1){
            return 1;
        }else{
            return (($page-1)*$limit) + 1;
        }
    }

    public function setLastItemIndex($page, $limit, $firtItemIndex, $total){
        if(!isset($page) || $page == 1){
            return $limit;
        }
        else{
            $value = $firtItemIndex + ($limit - 1);
            if($diff = $total - $value < 0)
                return $total;
            return $value;
        }
    }
}
