<?php

namespace App\Form;

use App\Entity\Location;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class LocationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('countryCode',ChoiceType::class , [
                'choices' => [
                    'Choose' => null,
                    'Austria' => 'AT',
                    'Germany' => 'DE',
                    'France' => 'FR',
                    'India' => 'IN',
                    'Poland' => 'PL',
                    'United Kingdom' => 'UK',
                    'United States' => 'US',  
                ]
            ])
            ->add('latitude')
            ->add('longitude')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Location::class,
        ]);
    }

}
