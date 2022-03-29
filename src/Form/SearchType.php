<?php

namespace App\Form;

use App\Entity\Adresse;
use App\Entity\Marque;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('searchBar', TextType::class, [
                'required' => false,
                'label' => 'Barre de recherche',
                'attr' => ['class' => 'form-control']
            ])
            ->add('marque', EntityType::class, [
                'required' => false,
                'label' => 'Marque',
                'class' => Marque::class,
                'attr' => ['class' => 'form-control']
            ])
            ->add('prixMin', NumberType::class, [

                'attr' => ['class' => 'form-control'],
                'label' => 'Prix minimum',
                'required' => false
            ])
            ->add('prixMax', NumberType::class, [

                'attr' => ['class' => 'form-control'],
                'label' => 'Prix maximum',
                'required' => false
            ])
            ->add('ordre', ChoiceType::class, [
                'choices' => [
                    'Prix croissant' => 1,
                    'Prix décroissant' => 2,
                ],
                'multiple' => false,
                'required' => false,
                'expanded' => false,
                'label' => 'Trier par :',
                'attr' => ['class' => 'form-control']
            ])
            ->add('Chercher', SubmitType::class, [
                'attr' => ['class' => 'btn btn-outline-dark']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
