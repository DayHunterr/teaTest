<?php
// src/Controller/MailingController.php
namespace App\Controller;

use App\Entity\Company;
use App\Form\CompanyType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MailingController extends AbstractController
{

    /**
     * @return Response
     */
    public function renderQuestionForm(): Response
    {
        return $this->render('subscribe/form.html.twig', [
            'form' => $this->createForm(CompanyType::class)->createView()
        ]);
    }


    /**
     * @Route("/subscribe", name="mailing_subscribe", methods={"POST"})
     */
    public function subscribe(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $company = new Company();
        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($company);
            $em->flush();

            return new JsonResponse([
                'success' => true,
                'message' => 'Thank you for subscribing!'
            ]);
        }

        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[] = $error->getMessage();
        }

        return new JsonResponse([
            'success' => false,
            'errors' => $errors
        ], 400);
    }
}
