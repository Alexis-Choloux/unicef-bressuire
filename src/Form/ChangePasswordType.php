<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'disabled' => true,
                'attr' => [
                    'class' => 'mb-3'
                ]
            ])
            ->add('old_password', PasswordType::class, [
                'label' => 'Ancien mot de passe', 
                'mapped' => false,
                'attr' => [
                    'placeholder' => 'Saisissez ici votre mot de passe actuel',
                    'class' => 'mb-3'
                ]
            ])
            ->add('new_password', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'invalid_message' => 'Le mot de passe et la confirmation du mot de passe doivent être identique !',
                'required' => true,
                'first_options' => [ 
                    'label' => 'Nouveau mot de passe',
                    'constraints' => new Length([
                        'min' => 2,
                        'max' => 20
                    ]),
                    'attr' => [
                        'placeholder' => 'Saisissez ici votre nouveau mot de passe',
                        'class' => 'mb-3'
                    ]
                ],
                'second_options' => [ 
                    'label' => 'Confirmez votre nouveau mot de passe',
                    'attr' => [
                        'placeholder' => 'Resaisissez ici votre nouveau mot de passe',
                        'class' => 'mb-3'
                    ]
                    ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Modifier le mot de passe',
                'attr' => [
                    'class' => 'btn btn-uniblue mt-2'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
