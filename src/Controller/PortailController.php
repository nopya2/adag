<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
// Include paginator interface
use Knp\Component\Pager\PaginatorInterface;
use App\Entity\User;
use App\Entity\Service;
use App\Entity\Projet;
use App\Entity\Blog;
use App\Entity\Vue;
use App\Entity\Evenement;
use App\Entity\Video;
use App\Entity\CommentaireArticle;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Repository\ServiceRepository;
use App\Repository\ProjetRepository;
use App\Repository\ConfigurationRepository;
use App\Repository\BlogRepository;
use App\Repository\PartenaireRepository;
use App\Repository\EvenementRepository;
use App\Repository\SliderRepository;
use App\Repository\RubriqueRepository;
use App\Repository\FlashRepository;
use App\Repository\ArticleRepository;
use App\Repository\VueRepository;
use App\Repository\VideoRepository;

class PortailController extends AbstractController {

    /**
     * @Route("/", name="home")
     */
    public function index(
        Request $request, RubriqueRepository $rubriqueRepos,
        FlashRepository $flashRepos, ArticleRepository $articleRepos, PartenaireRepository $partenaireRepos
    ) {

        //La liste des rubriques du menu
        $rubriques = $rubriqueRepos->findRubriquesWithArticles();

        $flashes = $flashRepos->find5lastFlashes();
        $lastArticles = $articleRepos->findlastArticles(4);
//        $last6Articles = $articleRepos->findlastArticles(6);
        $mostPopularArticles = $articleRepos->findMostPopular(5);
        $partenaires = $partenaireRepos->findAll();

        $rubriquesAndArticles = $rubriqueRepos->findRubriquesWithArticles();

        return $this->render('portail/home.html.twig', array(
            'rubriques' => $rubriques,
            'flashes' => $flashes,
            'last_articles' => $lastArticles,
//            'last_6_articles' => $last6Articles,
            'most_popular_articles' => $mostPopularArticles,
            'rubriques_with_articles' => $rubriquesAndArticles,
            'partenaires' => $partenaires,
            'page' => 'home',
            'sub_page' => 'home'
        ));
    }

    /**
     * @Route("/videos/", name="videos")
     */
    public function videos(
        Request $request, RubriqueRepository $rubriqueRepos,
        FlashRepository $flashRepos, ArticleRepository $articleRepos, PaginatorInterface $paginator,
        VideoRepository $videoRepos
    ) {

        //La liste des rubriques du menu
        $rubriques = $rubriqueRepos->findRubriquesWithArticles();

        $mostPopularArticles = $articleRepos->findMostPopular(3);

        $qVideos = $videoRepos->findAllByDate();

        $videos = $paginator->paginate(
            $qVideos, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );

        // $rubrique = $rubriqueRepos->findOneBy(['slug' => $request->get('rubrique')]);

        //On va selectionner les articles par rubrique et par date
//        $articles = $articleRepos->findlastArticlesByRubrique($rubrique->getId(), 11);

        //Liste des articles par rubrique
        // $qArticles = $articleRepos->querylastArticlesByRubrique($rubrique->getId());
        // $articles = $paginator->paginate(
        //     $qArticles, /* query NOT result */
        //     $request->query->getInt('page', 1), /*page number*/
        //     11 /*limit per page*/
        // );

        return $this->render('portail/page/video/index.html.twig', array(
            // 'rubrique' => $rubrique,
            'rubriques' => $rubriques,
            'articles_populaires' => $mostPopularArticles,
            'videos' => $videos,
            // 'articles' => $articles,
            'page' => 'videos',
            'sub_page' => 'videos',
        ));
    }

    /**
     * @Route("/rubriques/{rubrique}", name="rubrique")
     */
    public function rubrique(
        Request $request, RubriqueRepository $rubriqueRepos,
        FlashRepository $flashRepos, ArticleRepository $articleRepos, PaginatorInterface $paginator
    ) {

        //La liste des rubriques du menu
        $rubriques = $rubriqueRepos->findRubriquesWithArticles();

        $rubrique = $rubriqueRepos->findOneBy(['slug' => $request->get('rubrique')]);

        //On va selectionner les articles par rubrique et par date
//        $articles = $articleRepos->findlastArticlesByRubrique($rubrique->getId(), 11);

        //Liste des articles par rubrique
        $qArticles = $articleRepos->querylastArticlesByRubrique($rubrique->getId());
        $articles = $paginator->paginate(
            $qArticles, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            11 /*limit per page*/
        );

        return $this->render('portail/page/rubrique/index.html.twig', array(
            'rubrique' => $rubrique,
            'rubriques' => $rubriques,
            'articles' => $articles,
            'page' => $rubrique->getSlug(),
            'sub_page' => 'home',
        ));
    }

    /**
     * @Route("/articles/{article}", name="article")
     */
    public function article(
        Request $request, RubriqueRepository $rubriqueRepos,
        ArticleRepository $articleRepos, VueRepository $vueRepos
    ) {
        $article = $articleRepos->findOneBy(['slug' => $request->get('article')]);

        $mostPopularArticles = $articleRepos->findMostPopular(3);

        //La liste des rubriques du menu
        $rubriques = $rubriqueRepos->findRubriquesWithArticles();

        //On verifie si cette adresse IP n'a pas encore vue cette page
        //S'il n'a pas de vue on enregistre les vues
        $vue = $vueRepos->findOneBy(['ip'=>$request->getClientIp(), 'article'=>$article->getId()]);
        if(empty($vue)){
            $vue = new Vue();
            $vue->setIp($request->getClientIp());
            $vue->setArticle($article);
            $vue->setCreatedAt(new \DateTime());

            $em = $this->getDoctrine()->getManager();
            $em->persist($vue);
            $em->flush();
        }

        //Obtenir l'url courant
        $url = $request->getUri();

        //On poste un commentaire
        if($request->isMethod('post')){
            $commentaire = new CommentaireArticle();
            $commentaire->setArticle($article);
            $commentaire->setCreatedAt(new \DateTime());
            $commentaire->setNom($request->get('nom'));
            $commentaire->setEmail($request->get('email'));
            $commentaire->setContenu($request->get('contenu'));
            $commentaire->setSiteweb('siteweb');
            $commentaire->setType('externe');

            $em = $this->getDoctrine()->getManager();
            $em->persist($commentaire);
            $em->flush();

            $this->addFlash(
                'succes',
                'Votre commentaire a été posté!'
            );

            return $this->redirectToRoute('article', ['article' => $article->getSlug()]);
        }

        return $this->render('portail/page/article/show.html.twig', array(
            'rubriques' => $rubriques,
            'articles_populaires' => $mostPopularArticles,
            'article' => $article,
            'url' => $url,
            'page' => $article->getRubrique()->getSlug(),
            'sub_page' => 'home',
        ));
    }


    /**
     * @Route("/apropos", name="apropos")
     */
    public function about() {

        return $this->render('portail/apropos.html.twig', array(
            'page' => 'apropos',
        ));
    }

    /**
     * @Route("/presentation", name="presentation")
     */
    public function presentation() {

        return $this->render('portail/page/presentation.html.twig', array(
            'page' => 'presentation',
        ));
    }

    /**
     * @Route("/missions", name="missions")
     */
    public function missions() {

        return $this->render('portail/page/missions.html.twig', array(
            'page' => 'missions',
        ));
    }

    /**
     * @Route("/mecanisation", name="mecanisation")
     */
    public function mecanisation() {

        return $this->render('portail/page/mecanisation.html.twig', array(
            'page' => 'missions',
        ));
    }

    /**
     * @Route("/prestations", name="prestations")
     */
    public function prestations(ServiceRepository $serviceRepos ) {
        $prestations = $serviceRepos->findAll();

        return $this->render('portail/page/prestations.html.twig', array(
            'page' => 'prestations',
            'prestations' => $prestations
        ));
    }

    /**
     * @Route("/contacts", name="contacts")
     */
    public function contacts(ConfigurationRepository $configurationRepos) {
        $configuration = $configurationRepos->find(1);

        return $this->render('portail/page/contacts.html.twig', array(
            'page' => 'contacts',
            'configuration' => $configuration
        ));
    }

    /**
     * @Route("/services", name="services")
     */
    public function services(ServiceRepository $serviceRepos ) {
        $services = $serviceRepos->findVisibleServices();

        return $this->render('portail/page/service/index.html.twig', array(
            'page' => 'service',
            'services' => $services
        ));
    }

    /**
     * @Route("/services/{id}", name="service_details", methods="GET")
     */
    public function service(Service $service, ServiceRepository $serviceRepos, 
        ConfigurationRepository $configurationRepos
    ) {
        $configuration = $configurationRepos->find(1);
        $services = $serviceRepos->findVisibleServices();

        return $this->render('portail/page/service/details.html.twig', array(
            'page' => 'service_details',
            'service' => $service,
            'services' => $services,
            'configuration' => $configuration
        ));
    }

    /**
     * @Route("/projets", name="projets")
     */
    public function projets(ProjetRepository $projetRepos, ServiceRepository $serviceRepos) {
        $services = $serviceRepos->findVisibleServices();
        $projets = $projetRepos->findVisibleProjets();

        return $this->render('portail/page/projet/index.html.twig', array(
            'page' => 'projet',
            'services' => $services,
            'projets' => $projets
        ));
    }

    /**
     * @Route("/projets/{id}", name="projet_details")
     */
    public function projet(Projet $projet) {

        return $this->render('portail/page/projet/details.html.twig', array(
            'page' => 'projet_details',
            'projet' => $projet
        ));
    }

    /**
     * @Route("/actualites", name="actualites")
     */
    public function actualites(BlogRepository $blogRepos) {
        $blogs = $blogRepos->findVisibleBlogs();

        return $this->render('portail/page/blog/index.html.twig', array(
            'page' => 'actualite',
            'blogs' => $blogs,
        ));
    }

    /**
     * @Route("/actualites/{id}", name="actualite_details")
     */
    public function actualite(Blog $blog, BlogRepository $blogRepos, ProjetRepository $projetRepos) {
        $previousBlog = $blogRepos->getPreviousBlog($blog->getId());
        $nextBlog = $blogRepos->getNextBlog($blog->getId());
        $projets = $projetRepos->findRecentsProjets(4);
        $blogs = $blogRepos->findRecentsPosts(4);

        return $this->render('portail/page/blog/details.html.twig', array(
            'page' => 'actualite_details',
            'blog' => $blog,
            'previousBlog' => $previousBlog,
            'nextBlog' => $nextBlog,
            'projets' => $projets,
            'blogs' => $blogs
        ));
    }

    /**
     * @Route("/evenements", name="evenements")
     */
    public function evenements(EvenementRepository $evenementRepos) {
        $evenements = $evenementRepos->findRecentEvenements(10);

        return $this->render('portail/page/evenement/index.html.twig', array(
            'page' => 'evenement',
            'evenements' => $evenements,
        ));
    }

    /**
     * @Route("/evenements/{id}", name="evenement_details")
     */
    public function evenement(Evenement $evenement, BlogRepository $blogRepos, ProjetRepository $projetRepos) {

        return $this->render('portail/page/evenement/details.html.twig', array(
            'page' => 'evenement_details',
            'evenement' => $evenement,
        ));
    }

    
    public function topBar(ConfigurationRepository $configurationRepos) {
        $configuration = $configurationRepos->find(1);

        return $this->render('portail/include/_topbar.html.twig', array(
            'configuration' => $configuration
        ));
    }

    public function bottom(ConfigurationRepository $configurationRepos, BlogRepository $blogRepos, $max) {
        $configuration = $configurationRepos->find(1);
        $blogs = $blogRepos->findRecentsPosts(3);

        return $this->render('portail/include/_bottom.html.twig', array(
            'configuration' => $configuration,
            'blogs' => $blogs
        ));
    }

    public function partenaires(PartenaireRepository $partenaireRepos) {
        $partenaires = $partenaireRepos->findAll();

        return $this->render('portail/include/_partenaire.html.twig', array(
            'partenaires' => $partenaires
        ));
    }

    public function footer(ConfigurationRepository $configurationRepos, RubriqueRepository $rubriqueRepos,
        ArticleRepository $articleRepos) {
        $rubriques = $rubriqueRepos->findBy([], ['priority' => 'ASC']);
        $mostPopularArticles = $articleRepos->findMostPopular(3);
        $currentDate = new \DateTime();

        return $this->render('portail/include/_footer.html.twig', array(
            'currentDate' => $currentDate,
            'rubriques' => $rubriques,
            'articles' => $mostPopularArticles
        ));
    }

}