<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'nom : ',
                'attr' => [
                    'placeholder' => 'entrer votre nom'
                ]
            ])
            ->add('prenom', TextType::class, [
                'label' => 'prenom : ',
                'attr' => [
                    'placeholder' => 'entrer votre prenom'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'email : ',
                'attr' => [
                    'placeholder' => 'email@email.com'
                ]
            ])
            ->add('telephone', NumberType::class, [
                'label' => 'votre N° Tel : ',
                'attr' => [
                    'placeholder' => '0xxxxxxxxx'
                ]
            ])
            ->add('password', RepeatedType::class, [
                'label' => ' mot de passe : ',
                'type' => PasswordType::class,
                'label' => 'votre mot de passe',
                'required' => true,
                'first_options' => ['label' => 'mot de passe'],
                'second_options' => ['label' => 'confirmer votre mot de passe']
            ])
            ->add('submit', SubmitType::class, [
                'label' => 's\'inscrire'
            ])
            ->add('reset', ResetType::class, [
                'label' => 'reset'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
