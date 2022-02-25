<?php

namespace App\Form;

use App\Entity\Souscategorie;
use App\Entity\Subsouscategorie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SubsouscategorieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('souscategorie', EntityType::class, [
                'class' => Souscategorie::class,
                'choice_label' => function (Souscategorie $souscategorie) {
                    return $souscategorie->getNom() . ' - ' . $souscategorie->getCategorie();
                },
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Subsouscategorie::class,
        ]);
    }


}
