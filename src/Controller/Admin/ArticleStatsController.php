<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleStatsController extends AbstractController
{
    /**
     * @Route("/admin/article-stats", name="admin_article_stats")
     * @param EntityManagerInterface $em
     * @return Response
     * @throws Exception
     */
    public function index(EntityManagerInterface $em): Response
    {
        $conn = $em->getConnection();
        $sql = "SELECT DAYOFWEEK(created_at) as day, COUNT(id) as total FROM article GROUP BY day";
        $stmt = $conn->prepare($sql);
        $result = $stmt->executeQuery()->fetchAllAssociative();

        $data = array_fill(1, 7, 0);
        foreach ($result as $row) {
            $data[(int) $row['day']] = (int) $row['total'];
        }

        $labels = ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'];

        $orderedData = [
            $data[1] ?? 0, // Вс
            $data[2] ?? 0, // Пн
            $data[3] ?? 0,
            $data[4] ?? 0,
            $data[5] ?? 0,
            $data[6] ?? 0,
            $data[7] ?? 0, // Сб
        ];

        return $this->render('/articles/graph.html.twig', [
            'orderedData' => $orderedData,
        ]);
    }
}