<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class UserAdmin extends AbstractAdmin
{

    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->add('username', TextType::class)
            ->add('email', TextType::class)
            ->add('plainPassword', PasswordType::class, [
                'required' => false,
                'label' => 'Password',
            ])
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'User' => 'ROLE_USER',
                    'Admin' => 'ROLE_ADMIN',
                    'Super Admin' => 'ROLE_SUPER_ADMIN',
                ],
                'multiple' => true,
                'expanded' => true,
                'label' => 'Roles',
            ])
            ->add('enabled', CheckboxType::class, [
                'required' => false,
                'label' => 'Enabled'
            ]);
    }

    protected function configureDatagridFilters(DatagridMapper $datagrid): void
    {
        $datagrid->add('username');
        $datagrid->add('email');
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->addIdentifier('username')
            ->add('email')
            ->add('createdAt', null, [
                'format' => 'Y-m-d H:i:s'
            ])
            ->add('updatedAt', null, [
                'format' => 'Y-m-d H:i:s'
            ]);
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('username')
            ->add('email')
            ->add('createdAt', null, [
                'format' => 'Y-m-d H:i:s'
            ])
            ->add('updatedAt', null, [
                'format' => 'Y-m-d H:i:s'
            ]);
    }
}