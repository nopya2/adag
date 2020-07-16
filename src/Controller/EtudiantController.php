<?php

namespace App\Controller;

use App\Entity\Etudiant;
use App\Entity\Import;
use App\Form\EtudiantType;
use App\Form\ImportType;
use App\Repository\EtudiantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use PhpOffice\PhpSpreadsheet\SpreadSheet;
use PhpOffice\PhpSpreadsheet\Reader\Csv as ReaderCsv;
use PhpOffice\PhpSpreadsheet\Reader\Ods as ReaderOds;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as ReaderXlsx;

/**
 * @Route("/admin/etudiant")
 */
class EtudiantController extends Controller
{
    /**
     * @Route("/", name="admin_etudiant_index", methods="GET")
     */
    public function index(EtudiantRepository $etudiantRepository): Response
    {
        // $filename = $this->get('kernel')->getRootDir().'/../export/Browser_characteristics.xlsx';
        $filename = $this->get('kernel')->getProjectDir() . '/public/uploads/imports/5ca3198f3af73_etudiant.xlsx';

        if (!file_exists($filename)) {
            throw new \Exception('File does not exist');
        }

        $spreadsheet = $this->readFile($filename);
        $data = $this->createDataFromSpreadsheet($spreadsheet);

        print_r($data['Feuil1']);
        die();

        // return $this->render('backend/etudiant/index.html.twig', [
        //     'etudiants' => $etudiantRepository->findAll(),
        //     'page' => "etudiant",
        //     'activePage' => "etudiant_liste"
        // ]);
    }

    /**
     * @Route("/new", name="etudiant_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $etudiant = new Etudiant();
        $form = $this->createForm(EtudiantType::class, $etudiant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($etudiant);
            $em->flush();

            return $this->redirectToRoute('etudiant_index');
        }

        return $this->render('etudiant/new.html.twig', [
            'etudiant' => $etudiant,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/importer", name="admin_etudiant_importer", methods="GET|POST")
     */
    public function importer(Request $request): Response
    {
        $import = new Import();
        $form = $this->createForm(ImportType::class, $import);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($import);
            $em->flush();

            $filename = $this->get('kernel')->getProjectDir() . '/public/uploads/imports/' . $import->getFileName();
            if (!file_exists($filename)) {
                throw new \Exception('Fichier inexistant');
            }
            $spreadsheet = $this->readFile($filename);
            $data = $this->createDataFromSpreadsheet($spreadsheet);

            print_r($data);
            die();
            //return $this->redirectToRoute('admin_etudiant_index');
        }

        return $this->render('backend/etudiant/importer.html.twig', [
            'import' => $import,
            'form' => $form->createView(),
            'page' => "etudiant",
            'activePage' => "etudiant_importer"
        ]);
    }

    /**
     * @Route("/{id}", name="etudiant_show", methods="GET")
     */
    public function show(Etudiant $etudiant): Response
    {
        return $this->render('etudiant/show.html.twig', ['etudiant' => $etudiant]);
    }

    /**
     * @Route("/{id}/edit", name="etudiant_edit", methods="GET|POST")
     */
    public function edit(Request $request, Etudiant $etudiant): Response
    {
        $form = $this->createForm(EtudiantType::class, $etudiant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('etudiant_edit', ['id' => $etudiant->getId()]);
        }

        return $this->render('etudiant/edit.html.twig', [
            'etudiant' => $etudiant,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="etudiant_delete", methods="DELETE")
     */
    public function delete(Request $request, Etudiant $etudiant): Response
    {
        if ($this->isCsrfTokenValid('delete'.$etudiant->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($etudiant);
            $em->flush();
        }

        return $this->redirectToRoute('etudiant_index');
    }

    protected function readFile($filename){
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        switch ($extension){
            case 'ods':
                $reader = new ReaderOds();
                break;
            case 'csv':
                $reader = new ReaderCsv();
                break;
            case 'xlsx':
                $reader = new ReaderXlsx();
                break;
            default:
                throw new \Exception("Invalide extension");
        }
        
        $reader->setReadDataOnly(true);
        return $reader->load($filename);
    }

    protected function createDataFromSpreadsheet($spreadsheet){
        $data = [];
        foreach ($spreadsheet->getWorksheetIterator() as $worksheet){
            $worksheetTitle = $worksheet->getTitle();
            $data[$worksheetTitle] = [
                'columnNames' => [],
                'columnValues' => []
            ];
            foreach ($worksheet->getRowIterator() as $row) {
                $rowIndex = $row->getRowIndex();
                if($rowIndex > 1){
                    $data[$worksheetTitle]['columnValues'][$rowIndex] = [];
                }
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false);
                foreach ($cellIterator as $cell) {
                    if ($rowIndex === 1) {
                        $data[$worksheetTitle]['columnNames'][] = $cell->getCalculatedValue();
                    }
                    if ($rowIndex > 1) {
                        $data[$worksheetTitle]['columnValues'][$rowIndex][] = $cell->getCalculatedValue();
                    }
                }
            }
        }

        return $data;
    }
}
