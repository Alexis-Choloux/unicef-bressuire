<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\CacAddress;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $options['user'];

        $builder
            ->add('addresses', EntityType::class, [
                'label' => false,
                'required' => true,
                'class' => Address::class,
                'choices' => $user->getAddresses(),
                'multiple' => false,
                'expanded' => true,
                'attr' => [
                    'class' => 'mt-2'
                ]
            ])
            ->add('cacaddresses', EntityType::class, [
                'label' => false,
                'required' => true,
                'class' => CacAddress::class,
                'multiple' => false,
                'expanded' => true,
                'attr' => [
                    'class' => 'mt-2'
                ]
            ])
            ->add('contact_preference', ChoiceType::class, [
                'choices' => [
                    'Email' => 'Email',
                    'SMS' => 'SMS',
                    'Appel téléphonique' => 'Téléphone'
                ],
                'label' => false,
                'multiple' => false,
                'expanded' => true,
                'attr' => [
                    'class' => 'd-flex mt-2'
                ]
            ])
            ->add('availabilities', TextareaType::class, [
                'label' => false,
                'attr' => [
                    'class' => ' mt-2',
                    'placeholder' => "ex : 'Je suis disponible tous les soirs de la semaine, de 17h à 20h, ou les samedis toute la journée.'",
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider ma commande',
                'attr' => [
                    'class' => 'btn btn-danger col-6 mt-2',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'user' => array()
        ]);
    }
}
