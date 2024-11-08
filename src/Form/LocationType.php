<?php

namespace App\Form;

use App\Entity\Location;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LocationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('city')
            ->add('country')
            ->add('latitude', NumberType::class, [
                'scale' => 7, // Precyzja dla liczb zmiennoprzecinkowych
                'html5' => true,
                'attr' => [
                    'step' => '0.0000001', // Pozwala na wprowadzenie do 7 miejsc po przecinku
                    'placeholder' => 'Enter latitude (-90 to 90)',
                ],
            ])
            ->add('longitude', NumberType::class, [
                'scale' => 7, // Precyzja dla liczb zmiennoprzecinkowych
                'html5' => true,
                'attr' => [
                    'step' => '0.0000001', // Pozwala na wprowadzenie do 7 miejsc po przecinku
                    'placeholder' => 'Enter longitude (-180 to 180)',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Location::class,
        ]);
    }
}
