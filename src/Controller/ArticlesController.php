<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticlesController extends AbstractController
{

    /**
     * @Route("/articles", name="articles", options={"sitemap" = true})
     *
     * @return Response
     */
    public function index(Request $request,ArticleRepository $articleRepository, PaginatorInterface $paginator){
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
        ]);
    }

}