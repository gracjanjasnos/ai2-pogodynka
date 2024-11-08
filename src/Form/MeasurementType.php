<?php

namespace App\Form;

use App\Entity\Location;
use App\Entity\Measurement;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MeasurementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date', DateType::class, [
                'widget' => 'single_text', 
                'html5' => true, 
                'attr' => ['class' => 'form-control'], 
            ])
            ->add('celsius', NumberType::class, [
                'scale' => 1,
                'html5' => true, 
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Enter temperature in Celsius', 
                ],
            ])
            ->add('location', EntityType::class, [
                'class' => Location::class,
                'choice_label' => 'city', 
                'attr' => ['class' => 'form-control'], 
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Measurement::class,
        ]);
    }
}
