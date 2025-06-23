<?php

namespace App\Controller\Admin;

use Doctrine\ORM\EntityManagerInterface;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

final class ArticleAdminController extends CRUDController
{
    public function cloneAction(Request $request, EntityManagerInterface $em): RedirectResponse
    {
        $id = $request->get($this->admin->getIdParameter());
        $article = $this->admin->getObject($id);

        if (!$article) {
            $this->addFlash('sonata_flash_error', 'Article not found.');
            return $this->redirectToRoute('admin_app_article_list');
        }

        $cloned = clone $article;
        $cloned->setTitle($article->getTitle() . ' [::CLONE::]');

        $em->persist($cloned);
        $em->flush();

        $this->addFlash('sonata_flash_success', 'Article cloned successfully.');

        return $this->redirectToRoute('admin_app_article_list');
    }
}
