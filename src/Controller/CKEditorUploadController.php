<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;


class CKEditorUploadController extends AbstractController
{

    /**
     * @param Request $request
     * @param SluggerInterface $slugger
     * @return Response
     * @Route("/admin/ckeditor/upload", name="ckeditor_upload",methods={"POST"})
     */
    public function upload(Request $request, SluggerInterface $slugger): Response
    {
        $user = $this->getUser();
        if (!$user || !$this->isGranted('ROLE_ADMIN')) {
            return new JsonResponse(['error' => ['message' => 'Access denied']], 403);
        }

        $uploadedFile = $request->files->get('upload');
        if (!$uploadedFile) {
            return new JsonResponse(['error' => ['message' => 'No file uploaded']], 400);
        }

        $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $slugger->slug($originalFilename);
        $newFilename = $safeFilename . '-' . uniqid() . '.' . $uploadedFile->guessExtension();

        try {
            $uploadedFile->move(
                $this->getParameter('kernel.project_dir') . '/public/uploads/ckeditor',
                $newFilename
            );
        } catch (FileException $e) {
            return new Response('Upload failed', 500);
        }

        $funcNum = $request->query->get('CKEditorFuncNum');

        $url = '/uploads/ckeditor/' . $newFilename;

        $response = "<script type='text/javascript'>
        window.parent.CKEDITOR.tools.callFunction($funcNum, '$url', 'File successfully uploaded');
    </script>";

        return new Response($response, 200, ['Content-Type' => 'text/html']);
    }
//    public function upload(Request $request, SluggerInterface $slugger): Response
//    {
//        $user = $this->getUser();
//        if (!$user || !$this->isGranted('ROLE_ADMIN')) {
//            return $this->buildResponse($request, null, 'Access denied', 403);
//        }
//
//        $uploadedFile = $request->files->get('upload');
//        if (!$uploadedFile) {
//            return $this->buildResponse($request, null, 'No file uploaded', 400);
//        }
//
//        $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
//        $safeFilename = $slugger->slug($originalFilename);
//        $newFilename = $safeFilename . '-' . uniqid() . '.' . $uploadedFile->guessExtension();
//
//        try {
//            $uploadedFile->move(
//                $this->getParameter('kernel.project_dir') . '/public/uploads/ckeditor',
//                $newFilename
//            );
//        } catch (FileException $e) {
//            return $this->buildResponse($request, null, 'Upload failed', 500);
//        }
//
//        $url = '/uploads/ckeditor/' . $newFilename;
//
//        return $this->buildResponse($request, $url);
//    }
//
//    private function buildResponse(Request $request, ?string $url, string $errorMessage = '', int $status = 200): Response
//    {
//        var_dump($request);
//        if ($request->query->get('responseType') === 'json') {
//            if ($url) {
//                return new JsonResponse([
//                    'uploaded' => 1,
//                    'fileName' => basename($url),
//                    'url' => $url,
//                ]);
//            } else {
//                return new JsonResponse([
//                    'uploaded' => 0,
//                    'error' => ['message' => $errorMessage],
//                ], $status);
//            }
//        }
//
//        $funcNum = $request->query->get('CKEditorFuncNum');
//
//        if ($url) {
//            $html = "<script>window.parent.CKEDITOR.tools.callFunction($funcNum, '$url', 'File succesfully uploaded')</script>";
//        } else {
//            $html = "<script>window.parent.CKEDITOR.tools.callFunction($funcNum, '', '$errorMessage')</script>";
//        }
//
//        return new Response($html, $status, ['Content-Type' => 'text/html']);
//    }


}