<?php

namespace App\Form;

use App\Entity\Adresse;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AddAdresseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('address', TextType::class, [
                'label' => 'adresse',
                'attr' => [
                    'placeholder' => 'entrer votre adresse'
                ]
            ])
            ->add('codePostal', TextType::class, [
                'label' => 'code postale',
                'attr' => [
                    'placeholder' => 'entrer votre code postale'
                ]
            ])
            ->add('ville', TextType::class, [
                'label' => 'ville',
                'attr' => [
                    'placeholder' => 'entrer votre ville'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider',
                'attr' => [
                    'class' => 'btn btn-info'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Adresse::class,
        ]);
    }
}
