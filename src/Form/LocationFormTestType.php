<?php

namespace App\Form;

use App\Entity\Location;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class LocationFormTestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
                ->add('name',TextType::class)
                ->add('countryCode',ChoiceType::class , [
                    'choices' => [
                        '' => null,
                        'Austria' => 'AT',
                        'Poland' => 'PL',
                        'Germany' => 'DE',
                        'France' => 'FR',
                        'India' => 'IN',
                        'Spain' => 'ES',
                        'United Kingdom' => 'UK',
                        'United States' => 'US'
                    ]
                ])
                ->add('latitude',NumberType::class,[
                    'html5' => true,
                    'scale' => 7,
                    'attr' => [
                        'step' => 0.0001,
                        'min' => -90,
                        'max' => 90
                    ]
                ])
                ->add('longitude',NumberType::class,[
                    'html5' => true,
                    'scale' => 7,
                    'attr' => [
                        'step' => 0.0001,
                        'min' => -180,
                        'max' => 180
                    ]
                ])
                ->add('Submit',SubmitType::class)
                ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Location::class,
        ]);
    }
}
