<?php

namespace App\Controller;

use App\Entity\Video;
use App\Form\VideoType;
use App\Repository\VideoRepository;
use App\Entity\Commentaire;
use App\Form\CommentaireType;
use App\Repository\CommentaireRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/video")
 */
class VideoController extends AbstractController
{
    /**
     * @Route("/", name="admin_video_index", methods="GET")
     */
    public function index(VideoRepository $videoRepository, Request $request): Response
    {
        //Afficher ou Cacher un video
        // if($request->get('a') && $request->get('i')){
        //     $video = $videoRepository->find(intval($request->get('i')));
        //     if($request->get('a') === 'hide'){
        //         $video->setVisible(false);
        //         $message = 'Votre video est caché';
        //     }
        //     if($request->get('a') === 'show'){
        //         $video->setVisible(true);
        //         $message = 'Votre video est visible';
        //     }
        //     $this->getDoctrine()->getManager()->flush();

        //     $this->addFlash(
        //         'modification',
        //         $message
        //     );

        //     return $this->redirectToRoute('admin_service_index');
        // }

        return $this->render('backend/video/index.html.twig', [
            'videos' => $videoRepository->findAll(),
            'page' => "video",
            'activePage' => "video_liste"
        ]);
    }

    /**
     * @Route("/new", name="admin_video_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $video = new Video();
        $form = $this->createForm(VideoType::class, $video);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $video->setAuteur($this->getUser());
            $em = $this->getDoctrine()->getManager();
            $em->persist($video);
            $em->flush();

            return $this->redirectToRoute('admin_video_index');
        }

        return $this->render('backend/video/new.html.twig', [
            'video' => $video,
            'form' => $form->createView(),
            'page' => "video",
            'activePage' => "video_nouveau"
        ]);
    }

    // /**
    //  * @Route("/{id}", name="video_show", methods="GET")
    //  */
    // public function show(Video $video): Response
    // {
    //     return $this->render('backend/video/show.html.twig', ['video' => $video]);
    // }

    // /**
    //  * @Route("/{id}/edit", name="admin_video_edit", methods="GET|POST")
    //  */
    // public function edit(Request $request, Video $video): Response
    // {
    //     $pan = 'formulaire';

    //     if($request->query->get('pan'))
    //         $pan = $request->query->get('pan');

    //     $form = $this->createForm(VideoType::class, $video);
    //     $form->handleRequest($request);

    //     $commentaire = new Commentaire();
    //     $formCommentaire = $this->createForm(CommentaireType::class, $commentaire)
    //         ->remove('nom')
    //         ->remove('email')
    //         ->remove('website');
    //     $formCommentaire->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $video->setUpdatedAt(new \DateTime());
    //         $this->getDoctrine()->getManager()->flush();

    //         $this->addFlash(
    //             'succes',
    //             'Vos modifications ont été enregistrées!'
    //         );

    //         return $this->redirectToRoute('admin_video_edit', ['id' => $video->getId()]);
    //     }

    //     if ($formCommentaire->isSubmitted() && $formCommentaire->isValid()) {
    //         $commentaire->setNom('NEOSYS TECHNOLOGIE SUPPORT');
    //         $commentaire->setEmail('infos@neosystechnologie.ga');
    //         $commentaire->setWebsite('neosystechnologie.ga');
    //         $commentaire->setType('interne');
    //         $commentaire->setVideo($video);
    //         $commentaire->setSendedAt(new \DateTime());
    //         $em = $this->getDoctrine()->getManager();
    //         $em->persist($commentaire);
    //         $em->flush();

    //         return $this->redirectToRoute('admin_video_edit', ['id' => $video->getId(), 'pan' => $pan]);
    //     }

    //     return $this->render('backend/video/edit.html.twig', [
    //         'video' => $video,
    //         'form' => $form->createView(),
    //         'commentaire' => $commentaire,
    //         'formCommentaire' => $formCommentaire->createView(),
    //         'pan' => $pan,
    //         'page' => "video",
    //         'activePage' => "video_modifier"
    //     ]);
    // }

    /**
     * @Route("/{id}", name="admin_video_delete", methods="DELETE")
     */
    public function delete(Request $request, Video $video): Response
    {
        if ($this->isCsrfTokenValid('delete'.$video->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($video);
            $em->flush();
        }

        return $this->redirectToRoute('admin_video_index');
    }
}
