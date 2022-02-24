<?php

namespace App\Form;

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
                'attr' => ['class' => 'form-control']
            ])
            ->add('marque', EntityType::class, [
                'required' => false,
                'class' => Marque::class,
                'attr' => ['class' => 'form-control']
            ])
            ->add('prixMin', NumberType::class, [

                'attr' => ['class' => 'form-control'],
                'required' => false
            ])
            ->add('prixMax', NumberType::class, [

                'attr' => ['class' => 'form-control'],
                'required' => false
            ])
            ->add('ordre', ChoiceType::class, [
                'choices' => [
                    'Prix croissant' => 1,
                    'Prix décroissant' => 2,
                    'Popularité' => 3
                ],
                'multiple' => false,
                'required' => false,
                'expanded' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('save', SubmitType::class, [
                'attr' => ['class' => 'myButton mt-3']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
