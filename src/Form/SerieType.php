<?php

namespace App\Form;

use App\Entity\Serie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SerieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de la série',
                'required' => false,
                'attr' => [
                    'maxlength' => 255,
                    'class' => 'special-class',
                ],
                'row_attr' => [
                    'class' => 'input-group mb-3'
                ]
            ])
            ->add('overview', TextareaType::class, [
                'required' => false,
                'row_attr' => [
                    'class' => 'input-group mb-3'
                ]
            ])
            ->add('status', ChoiceType::class, [
                'required' => false,
                'choices' => [
                    'en cours' => 'returning',
                    'terminé' => 'ended',
                    'abandonné' => 'canceled',
                ],
                'row_attr' => [
                    'class' => 'input-group mb-3'
                ]
            ])
            ->add('popularity', TextType::class, [
                'required' => false,
                'row_attr' => [
                    'class' => 'input-group mb-3'
                ]
            ])
            ->add('genres')
            ->add('vote')
            ->add('firstAirDate', DateType::class, [
                'required' => false,
            ])
            ->add('lastAirDate', DateType::class, [
                'required' => false,
            ])
            ->add('backdrop')
            ->add('poster')
            ->add('tmdbId')
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Serie::class,
        ]);
    }
}
