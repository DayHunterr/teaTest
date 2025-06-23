<?php

namespace App\Controller;

use App\Entity\Order;
use App\Form\OrderType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class OrderController extends AbstractController
{
    /**
     * @return Response
     */
    public function renderOrderForm() : Response
    {
        $form = $this->createForm(OrderType::class);

        return $this->render('subscribe/form_order.html.twig', [
            'form' => $this->createForm(OrderType::class)->createView(),
        ]);
    }

    /**
     * @Route("/order/submit", name="order_submit", methods={"POST"})
     */
    public function submit(Request $request, EntityManagerInterface $em, ValidatorInterface $validator)
    {
        $order = new Order();
        $form = $this->createForm(OrderType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($order);
            $em->flush();

            return new JsonResponse([
                'success' => true,
                'message' => 'Your order has been placed!',
            ]);
        }

        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[] = $error->getMessage();
        }

        return new JsonResponse([
            'success' => false,
            'message' => implode("\n", $errors) ?: 'Invalid data.',
        ], 400);
    }
}