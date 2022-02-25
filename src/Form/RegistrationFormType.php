<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Unique;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'attr' => ['class' => 'ps-3 my-1 border border-1 border-dark rounded py-1'],
                'constraints' => [new NotBlank(), new Email(), new UniqueEntity(['fields' => 'email'])],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'mapped' => false,
                'type' => PasswordType::class,
                'invalid_message' => 'les mots de passe ne correspondent pas',
                'options' => ['attr' => ['class' => 'password-field ps-3 my-1 border border-1 border-dark rounded py-1']],
                'required' => true,
                'first_options' => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Confirmez votre mot de passe'],
                'constraints' => new NotBlank(),
            ])
            ->add('prenom', TextType::class, [
                'attr' => ['class' => 'ps-3 my-1 border border-1 border-dark rounded py-1', 'placeholder' => 'Prénom*'],
                'required' => true,
                'constraints' => new NotBlank(),

            ])
            ->add('nom', TextType::class, [
                'attr' => ['class' => 'ps-3 my-1 border border-1 border-dark rounded py-1', 'placeholder' => 'Nom*'],
                'required' => true,
                'constraints' => new NotBlank(),

            ])
            ->add('estHomme', ChoiceType::class, [
                'label' => 'Vous êtes :',
                'choices' => [
                    'Femme' => '0',
                    'Homme' => '1',
                ],
                'expanded' => true,
                'required' => true,
                'multiple' => false,
                'constraints' => new NotBlank(),
            ])
            ->add('dateNaissance', DateType::class, [
                'widget' => 'single_text',
                'html5' => true,
                'attr' => ['class' => 'js-datepicker'],
                'constraints' => new NotBlank(),
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
