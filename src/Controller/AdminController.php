<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;
use App\Repository\ServiceRepository;
use App\Repository\ProjetRepository;
use App\Repository\BlogRepository;
use App\Repository\EvenementRepository;
use App\Repository\PartenaireRepository;
use App\Repository\SliderRepository;
use App\Repository\ArticleRepository;
use App\Repository\CommentaireArticleRepository;
use App\Repository\RubriqueRepository;
// use App\Repository\ClientRepository;
// use App\Repository\ProduitRepository;
// use App\Repository\CommandeRepository;
// use App\Repository\TransactionRepository;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends AbstractController {

    /**
     * @Route("/admin", name="admin_dashboard")
     */
    public function index(Request $request, UserRepository $userRepository, ServiceRepository $serviceRepository, ProjetRepository $projetRepository, BlogRepository $blogRepository, EvenementRepository $evenementRepository,
        PartenaireRepository $partenaireRepository, SliderRepository $sliderRepository,
        ArticleRepository $articleRepos, CommentaireArticleRepository $commentaireRepos,
        RubriqueRepository $rubriqueRepos) {

        $nbrUsers = count($userRepository->findAll());
        $nbrServices = count($serviceRepository->findAll());
        $nbrProjets = count($projetRepository->findAll());
        $blogs = $blogRepository->findAll();
        $evenements = $evenementRepository->findAll();
        $partenaires = $partenaireRepository->findAll();
        $sliders = $sliderRepository->findAll();
        $articles = $articleRepos->findAll();
        $commentaires = $commentaireRepos->findAll();
        $rubriques = $rubriqueRepos->findAll();

        return $this->render('backend/dashboard.html.twig', array(
            'nbrUsers' => $nbrUsers,
            'nbrServices' => $nbrServices,
            'nbrProjets' => $nbrProjets,
            'blogs' => $blogs,
            'partenaires' => $partenaires,
            'sliders' => $sliders,
            'evenements' => $evenements,
        	'page' => 'dashboard',
        	'activePage' => 'dashboard',
            'articles' => $articles,
            'commentaires' => $commentaires,
            'rubriques' => $rubriques
        ));
        // return $this->render('security/test.html.twig');
    }

    /**
     * @Route("/admin/profile/{id}", name="admin_user_profile", methods="GET")
     */
    public function show(User $user, Request $request): Response
    {
        $form = $this->createForm(UserType::class, $user)
            ->remove('password');

        $formPassword = $this->createForm(UserType::class, $user)
            ->remove('nom')
            ->remove('prenom')
            ->remove('email')
            ->remove('username')
            ->remove('isActive')
            ->remove('roles')
            ->remove('fonction')
            ->remove('facebook')
            ->remove('googleplus')
            ->remove('twitter')
            ->remove('linkedin')
            ->remove('imageFile');

        $form->handleRequest($request);

        return $this->render('backend/user/profile.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'formPassword' => $formPassword->createView(),
            'page' => "utilisateur",
            'activePage' => "mon_profile"
        ]);
    }

    /**
     * @Route("/admin/update-profile/{id}", name="admin_update_profile", methods="PUT|POST|GET")
     */
    public function updateProfile(Request $request,  User $user): Response
    {
        $form = $this->createForm(UserType::class, $user)
            ->remove('roles')
            ->remove('isActive')
            ->remove('password');

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return new JsonResponse(array('user' => 1));
        } else {
            return new JsonResponse(array('form' => 0));
        }
    }

    /**
     * @Route("/admin/update-profile-password/{id}", name="admin_update_profile_password", methods="PUT|POST")
     */
    public function updateProfilePassword(Request $request,  User $user, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $form = $this->createForm(UserType::class, $user)
            ->remove('nom')
            ->remove('prenom')
            ->remove('email')
            ->remove('username')
            ->remove('isActive')
            ->remove('roles')
            ->remove('imageFile');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);
            $this->getDoctrine()->getManager()->flush();
            return new JsonResponse(array('user' => 1));
        } else {
            return new JsonResponse(array('form' => 0));
        }
    }

}