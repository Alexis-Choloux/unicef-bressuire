<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\Length;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'Prénom :',
                'constraints' => new Length([
                    'min' => 2,
                    'max' => 20
                ]),
                'attr' => [
                    'placeholder' => 'Saisissez ici votre prénom',
                    'class' => 'mb-2'
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom :',
                'constraints' => new Length([
                    'min' => 2,
                    'max' => 20
                ]),
                'attr' => [
                    'placeholder' => 'Saisissez ici votre nom',
                    'class' => 'mb-2'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Adresse e-mail (courriel) :',
                'constraints' => new Length([
                    'min' => 2,
                    'max' => 50
                ]),
                'attr' => [
                    'placeholder' => 'exemple@exemple.com',
                    'class' => 'mb-2'
                ]
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Le mot de passe et la confirmation du mot de passe doivent être identique !',
                'required' => true,
                'first_options' => [ 
                    'label' => 'Mot de passe :',
                    'constraints' => new Length([
                        'min' => 2,
                        'max' => 20
                    ]),
                    'attr' => [
                        'placeholder' => 'Saisissez ici votre mot de passe',
                        'class' => 'mb-2'
                    ]
                ],
                'second_options' => [ 
                    'label' => 'Confirmez votre mot de passe :',
                    'attr' => [
                        'placeholder' => 'Saisissez à nouveau votre mot de passe'
                    ]
                    ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'S\'inscire',
                'attr' => [
                    'class' => 'btn btn-uniblue mt-3'
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
