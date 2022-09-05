<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('subject', ChoiceType::class, [
                'label' => 'Séléctionnez le sujet',
                'choices' => [
                    'Devenir bénévole' => 'Demande de bénévolat',
                    'Question(s) concernant la boutique en ligne/Click&Collect' => 'Question boutique/Click&Collect',
                    'Question(s) liée(s) à Unicef' => 'Question Unicef',
                    'Demande d\'annulation de commande' => 'Annulation de commande',
                    'Demande de suppression de compte' => 'Demande suppression de compte',
                    'Autre(s)' => 'Demande'
                ],
                'multiple' => false,                
                'attr' => [
                    'class' => 'mb-3'
                ]
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'attr' => [
                    'placeholder' => 'ex. : Henrietta',
                    'class' => 'mb-3'
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => 'ex. : H. Fore',
                    'class' => 'mb-3'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => [
                    'placeholder' => 'exemple@unicef.fr',
                    'class' => 'mb-3'
                ]
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Message',
                'attr' => [
                    'placeholder' => 'Ecrivez votre message ici...',
                    'class' => 'mb-3'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Envoyer',
                'attr' => [
                    'class' => 'col-5 btn-uniblue'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
