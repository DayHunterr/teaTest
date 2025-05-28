<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

class HomePageController extends AbstractController
{
    /**
     * @Route("/", name="home", options={"sitemap" = true})
     *
     * @param ArticleRepository $articleRepository
     * @param Breadcrumbs $breadcrumbs
     * @return Response
     */
    public function index(ArticleRepository $articleRepository, Breadcrumbs $breadcrumbs): Response
    {
        $breadcrumbs->addItem('Home');

        $latestArticles = $articleRepository->findBy([], ['id' => 'ASC'], 4);

        return $this->render('home_page/index.html.twig', [
            'latestArticles' => $latestArticles,
            'breadcrumbs' => $breadcrumbs,
        ]);
    }

}