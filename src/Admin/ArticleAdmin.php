<?php

namespace App\Admin;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\RequestStack;

final class ArticleAdmin extends AbstractAdmin
{

//    private RequestStack $requestStack;
//
//    public function __construct(RequestStack $requestStack)
//    {
//        parent::__construct();
//        $this->requestStack = $requestStack;
//    }

    protected function configureFormFields(FormMapper $form): void
    {

//        $request = $this->requestStack->getCurrentRequest();
//        if ($request && $request->query->get('responseType') === 'json') {
//            $configName = 'drag_and_drop';
//        } else{
//            $configName = 'default';
//        }



        $form->add('title', TextType::class);
        $form->add('author', TextType::class);
        $form->add('text', CKEditorType::class, [
            'label' => 'Content',
            'config_name' => 'default'
        ]);


    }

    protected function configureDatagridFilters(DatagridMapper $datagrid): void
    {
        $datagrid->add('title');
        $datagrid->add('author');
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list->addIdentifier('title');
        $list->addIdentifier('author');
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show->add('title');
        $show->add('author');
        $show->add('text');
    }
}