<?php

namespace App\Form;

use App\Entity\ValueObject\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('country', CountryType::class, [
                'label' => 'Pays',
                'placeholder' => 'Choose a country',
                'preferred_choices' => ['FR', 'US'],
            ])
            ->add('addressLine1')
            ->add('addressLine2')
            ->add('city')
            ->add('postalCode')
            ->add('state');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
