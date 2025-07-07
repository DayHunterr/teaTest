<?php

namespace App\Form;

use App\Entity\Order;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


final class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fullname', TextType::class, [
                'attr' => [
                    'class' => 'order-list__field',
                ],
            ])
            ->add('email', EmailType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'order-list__field',
                ],
            ])
            ->add('phone', TelType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'order-list__field',
                ],
            ])
            ->add('tea_type', ChoiceType::class, [
                'choices' => [
                    'Black Tea' => 'black tea',
                    'Green Tea' => 'green tea',
                    'White Tea' => 'white tea',
                    'Oolong Tea' => 'oolong tea',
                    'Pu-erh Tea' => 'pu-erh tea',
                ],
                'attr' => [
                    'class' => 'order-list__field',
                ],
            ])
            ->add('quantity', TextType::class, [
                'attr' => [
                    'class' => 'order-list__field',
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Order Now',
                'attr' => [
                    'class' => 'mailing-list__btn',
                ],
            ]);
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }

}