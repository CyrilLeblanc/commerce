<?php

namespace App\Form;

use App\Dto\Card;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CheckoutType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('number', NumberType::class, [
                'label' => 'Card number',
            ])
            ->add('year', IntegerType::class, [
                'label' => 'Year',
                'attr' => [
                    'min' => date('Y'),
                    'max' => date('Y') + 10,
                ],
            ])
            ->add('month', IntegerType::class, [
                'label' => 'Month',
                'attr' => [
                    'min' => 1,
                    'max' => 12,
                ]
            ])
            ->add('cvv', NumberType::class, [
                'label' => 'Cryptogram',
            ])
            ->add('name', TextType::class, [
                'label' => 'Name',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Card::class,
        ]);
    }
}
