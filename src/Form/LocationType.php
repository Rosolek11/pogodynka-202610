<?php

namespace App\Form;

use App\Entity\Location;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LocationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('city', TextType::class, ['label' => 'City'])
            ->add('latitude', NumberType::class, [
                'label' => 'Latitude', 'scale' => 7, 'input' => 'string', 'html5' => true,
            ])
            ->add('longitude', NumberType::class, [
                'label' => 'Longitude', 'scale' => 7, 'input' => 'string', 'html5' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Location::class]);
    }
}
