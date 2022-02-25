<?php

namespace App\Form;

use App\Entity\Adresse;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Positive;

class AdresseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nbRue', NumberType::class, [
                'constraints' => new Positive(),
            ])
            ->add('nomRue', TextType::class, [
                'constraints' => new NotBlank(),
            ])
            ->add('codePostal', TextType::class, [
                'constraints' => new NotBlank(),
            ])
            ->add('villeNom', TextType::class, [
                'constraints' => new NotBlank(),
            ])
            ->add('complement', TextType::class, [
                'required' => false
            ])
            ->add('user', RegistrationFormType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Adresse::class,
        ]);
    }
}
