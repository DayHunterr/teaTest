<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

class ArticlesController extends AbstractController
{

    /**
     * @Route("/articles", name="articles", options={"sitemap" = true})
     *
     * @param Request $request
     * @param ArticleRepository $articleRepository
     * @param PaginatorInterface $paginator
     * @param Breadcrumbs $breadcrumbs
     * @return Response
     */
    public function index(Request $request,ArticleRepository $articleRepository, PaginatorInterface $paginator, Breadcrumbs $breadcrumbs): Response
    {

        $breadcrumbs->addItem('Home', $this->generateUrl('home'));
        $breadcrumbs->addItem('Articles');

        $query = $articleRepository->createQueryBuilder('a')
            ->orderBy('a.id', 'DESC')
            ->getQuery();

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            20
        );

        return $this->render('articles/articles.html.twig', [
            'pagination' => $pagination,
            'breadcrumbs' => $breadcrumbs,
        ]);
    }

    /**
     * @param Article $article
     * @param Breadcrumbs $breadcrumbs
     * @return void
     * @Route("/article/{id}", name="article", methods={"GET"})
     *
     */
    public function showArticle(Article $article,  Breadcrumbs $breadcrumbs): Response
    {

        $breadcrumbs
            ->addItem('Home', $this->generateUrl('home'))
            ->addItem('Articles', $this->generateUrl('articles'))
            ->addItem($article->getTitle());

        return $this->render('articles/show.html.twig', [
            'article' => $article,
            'breadcrumbs' => $breadcrumbs,
        ]);
    }
}