<?php

namespace App\Admin;

use App\Controller\Admin\ArticleAdminController;
use App\Controller\Admin\DataTransformer\TagTransformer;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollectionInterface;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class ArticleAdmin extends AbstractAdmin
{
    protected $baseControllerName = ArticleAdminController::class;
    private TagTransformer $tagTransformer;

    public function __construct(TagTransformer $tagTransformer,
                                ?string        $code = null,
                                ?string        $class = null,
                                ?string        $baseControllerName = null)
    {
        parent::__construct($code, $class, $baseControllerName);
        $this->tagTransformer = $tagTransformer;
    }

    protected function configureFormFields(FormMapper $form): void
    {

        $form->add('title', TextType::class);
        $form->add('author', TextType::class);
        $form->add('text', CKEditorType::class, [
            'label' => 'Content',
            'config_name' => 'default'
        ]);
        $form->add('tags', TextType::class);
        $form->add('smallImageFile', FileType::class, [
            'label' => 'Small image (preview)',
            'required' => false,
            'mapped' => false,
        ]);

        $form->add('largeImageFile', FileType::class, [
            'label' => 'Large image (inside article)',
            'required' => false,
            'mapped' => false,
        ]);

        $form->get('tags')->addModelTransformer($this->tagTransformer);
    }

    protected function configureDatagridFilters(DatagridMapper $datagrid): void
    {
        $datagrid->add('id');
        $datagrid->add('title');
        $datagrid->add('author');
        $datagrid->add('tags');
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list->addIdentifier('id');
        $list->addIdentifier('title');
        $list->addIdentifier('author');

        $list->add('_action', 'actions', [
            'actions' => [
                'edit' => [],
                'delete' => [],
                'show' => [],
                'clone' => [
                    'template' => 'articles/list__action_clone.html.twig',
                ],
            ],
            'template' => '@SonataAdmin/CRUD/list__action.html.twig', // not necessary
        ]);
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show->add('id');
        $show->add('title');
        $show->add('author');
        $show->add('text');
        $show->add('tags');
    }

    public function prePersist($article): void
    {
        $this->handleImageUpload($article);
    }

    public function preUpdate($article): void
    {
        $this->handleImageUpload($article);
    }

    protected function configureRoutes(RouteCollectionInterface $collection): void
    {
        $collection->add('clone', $this->getRouterIdParameter() . '/clone');
    }

    private function handleImageUpload($article): void
    {
        $projectDir = dirname(__DIR__, 2);
        $form = $this->getForm();

        $smallImageFile = $form->get('smallImageFile')->getData();
        $largeImageFile = $form->get('largeImageFile')->getData();

        if ($smallImageFile instanceof UploadedFile) {
            $filename = uniqid() . '.' . $smallImageFile->guessExtension();
            $smallImageFile->move($projectDir . '/public/uploads/articles', $filename);
            $article->setSmallImage('/uploads/articles/' . $filename);
        }

        if ($largeImageFile instanceof UploadedFile) {
            $filename = uniqid() . '.' . $largeImageFile->guessExtension();
            $largeImageFile->move($projectDir . '/public/uploads/articles', $filename);
            $article->setLargeImage('/uploads/articles/' . $filename);
        }
    }
}