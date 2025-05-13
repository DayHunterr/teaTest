<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CKEditorController extends AbstractController
{
    /**
     * @param Request $request
     * @return JsonResponse
     * @Route("/ckeditor/upload", name="ckeditor_upload",methods={"POST"})
     */
    public function upload(Request $request): JsonResponse
    {
        $file = $request->files->get('upload');

        if (!$file) {
            return new JsonResponse(['uploaded' => 0, 'error' => ['message' => 'No file uploaded']], Response::HTTP_BAD_REQUEST);
        }

        $uploadDir = $this->getParameter('kernel.project_dir') . '/public/uploads/ckeditor';
        $filename = uniqid() . '.' . $file->guessExtension();

        try {
            $file->move($uploadDir, $filename);
        } catch (FileException $e) {
            return new JsonResponse(['uploaded' => 0, 'error' => ['message' => 'Upload failed']], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new JsonResponse([
            'uploaded' => 1,
            'fileName' => $filename,
            'url' => '/uploads/ckeditor/' . $filename,
        ]);
    }

}