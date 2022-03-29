<?php

namespace App\Form;

use App\Entity\Produit;
use App\Entity\Souscategorie;
use App\Entity\Subsouscategorie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('description')
            ->add('prixHt')
            ->add('idCategorie')
            ->add('idSousCategorie', EntityType::class, [
                'class' => Souscategorie::class,
                'choice_label' => function (Souscategorie $souscategorie) {
                    return $souscategorie->getNom() . ' - ' . $souscategorie->getCategorie();
                },
            ])
            ->add('idSubSousCategorie', EntityType::class, [
                'class' => Subsouscategorie::class,
                'required' => false
            ])
            ->add('idMarque')
            ->add('images', CollectionType::class, [
                'entry_type' => ImageType::class,
                'allow_add' => true,
                'prototype' => true
            ])
            ->add('estDispo', ChoiceType::class, [
                'choices' => [
                    'Disponible' => 1,
                    'Pas disponible' => 0
                ],
            ])
            ->add('Enregistrer', SubmitType::class, [
                'attr' => ['class' => 'btn mt-3']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
