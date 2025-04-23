<?php

namespace App\Form;

use App\Entity\Company;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class CompanyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => false,
                'required' => true,
                'attr' => [
                    'class' => 'mailing-list__email',
                    'placeholder' => 'subscribe.placeholder',
                ],
            ])
            ->add('agree', CheckboxType::class, [
                'mapped' => false,
                'label' => false,
                'required' => true,
                'attr' => [
                    'class' => 'checkbox',
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'mailing.button',
                'attr' => [
                    'class' => 'mailing-list__btn',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Company::class,
        ]);
    }
}
