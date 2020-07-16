<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Entity\Rubrique;
use App\Repository\RubriqueRepository;
use App\Repository\ConfigurationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Functions;

class SitemapController extends AbstractController
{
    /**
     * @Route("/sitemap/sitemap.xml", name="sitemap", defaults={"_format"="xml"})
     */
    public function show(ArticleRepository $articleRepository, Request $request, RubriqueRepository $rubriqueRepos): Response
    {
        $urls = array();
        $hostname = $request->getSchemeAndHttpHost();

        // add static urls
        $urls[] = array('loc' => $this->generateUrl('home'));
        $urls[] = array('loc' => $this->generateUrl('videos'));

        // add static urls with optional tags
        // $urls[] = array('loc' => $this->generateUrl('fos_user_security_login'), 'changefreq' => 'monthly', 'priority' => '1.0');
        // $urls[] = array('loc' => $this->generateUrl('cookie_policy'), 'lastmod' => '2018-01-01');

        // add dynamic urls, like article posts from your DB
        foreach ($articleRepository->findAll() as $article) {
            $urls[] = array(
                'loc' => $this->generateUrl('article', array('article' => $article->getSlug()))
            );
        }

        // add dynamic urls, like rubrique posts from your DB
        foreach ($rubriqueRepos->findAll() as $rubrique) {
            $urls[] = array(
                'loc' => $this->generateUrl('rubrique', array('rubrique' => $rubrique->getSlug()))
            );
        }

        // return response in XML format
        $response = new Response(
            $this->renderView('sitemap.html.twig', array( 'urls' => $urls,
                'hostname' => $hostname)),
            200
        );
        $response->headers->set('Content-Type', 'text/xml');
 
        return $response;
        
    }
}
