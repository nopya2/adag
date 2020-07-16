<?php

namespace App\Controller;

use App\Entity\Document;
use App\Form\DocumentType;
use App\Repository\DocumentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/document")
 */
class DocumentController extends AbstractController
{
    /**
     * @Route("/", name="admin_document_index", methods="GET")
     */
    public function index(DocumentRepository $documentRepository): Response
    {
        return $this->render('backend/document/index.html.twig', [
            'documents' => $documentRepository->findAll(),
            'page' => "document",
            'activePage' => "document_liste"
        ]);
    }

    /**
     * @Route("/new", name="admin_document_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $document = new Document();
        $form = $this->createForm(DocumentType::class, $document);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($document);
            $em->flush();

            $this->addFlash(
                'succes',
                'Votre document a été importé avec succès!'
            );

            return $this->redirectToRoute('admin_document_index');
        }

        return $this->render('backend/document/new.html.twig', [
            'document' => $document,
            'form' => $form->createView(),
            'page' => "document",
            'activePage' => "document_importer"
        ]);
    }

    /**
     * @Route("/{id}", name="document_show", methods="GET")
     */
    public function show(Document $document): Response
    {
        return $this->render('document/show.html.twig', ['document' => $document]);
    }

    /**
     * @Route("/{id}/edit", name="document_edit", methods="GET|POST")
     */
    public function edit(Request $request, Document $document): Response
    {
        $form = $this->createForm(DocumentType::class, $document);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('document_edit', ['id' => $document->getId()]);
        }

        return $this->render('document/edit.html.twig', [
            'document' => $document,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_document_delete", methods="DELETE")
     */
    public function delete(Request $request, Document $document): Response
    {
        if ($this->isCsrfTokenValid('delete'.$document->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($document);
            $em->flush();

            $this->addFlash(
                'suppression',
                'Votre document a été supprimé!'
            );
        }

        return $this->redirectToRoute('admin_document_index');
    }
}
