<?php

namespace App\Form;

use App\Entity\Purchase;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CartConfirmationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('fullName', TextType::class, [
            'label' => 'Nom complet',
            'attr' => [
                'placeholder' =>'Entrez vos nom et prénom'
            ]
        ])
        ->add('address', TextareaType::class, [
            'label' => 'Adresse Complète',
            'attr' => [
                'placeholder' => 'Entrez votre addresse (numéro, rue, bâtiment...)' 
            ]
        ])
        ->add('postalCode', TextType::class, [
            'label' => 'Code postal',
            'attr' => [
                'placeholder' => 'Entrez le code postal de la ville de livraison'
            ]
        ])
        ->add('city', TextType::class, [
            'label' => 'Ville',
            'attr' => [
                'placeholder' => 'Entrez votre ville de livraison'
            ]
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Purchase::class,
        ]);
    }
}
