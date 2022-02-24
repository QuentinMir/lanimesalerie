<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HeaderSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('rechercheNav', TextType::class, [
                'required' => false,
                'attr' =>
                    ['class' => 'search-h search-w'],
                /*['id' => 'site-search'],
                ['type' => 'search'],
                ['size' => '30'],
                ['placeholder' => 'Chercher sur le site']*/
            ])
            ->add('save', SubmitType::class, [
                'attr' => ['class' => 'ms-2 bouton-nav d-flex search-h']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
