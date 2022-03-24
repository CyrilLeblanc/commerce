<?php

namespace App\Form;

use App\Dto\Card;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CheckoutType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('number', NumberType::class, [
                'label' => 'Numéro de carte',
                'attr' => [
                    'placeholder' => 'Numéro de carte',
                ],
            ])
            ->add('date', DateType::class, [
                'label' => 'Date d\'expiration',
                'attr' => [
                    'placeholder' => 'Date d\'expiration',
                ],
            ])
            ->add('cvv', NumberType::class, [
                'label' => 'Code de sécurité',
                'attr' => [
                    'placeholder' => 'Code de sécurité',
                ],
            ])
            ->add('name', TextType::class, [
                'label' => 'Nom du titulaire',
                'attr' => [
                    'placeholder' => 'Nom du titulaire',
                ],
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
