<?php

namespace App\Form;

use App\Entity\ValueObject\Payment;
use App\Validator\CardExpirationDate;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;


class PaymentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('cardNumber',TextType::class,[
                'constraints' => [
                    new Assert\Regex([
                        'pattern' => '/^(\d{4}[- ]?){3}\d{4}$/',
                        'message' => 'Le numéro de carte doit contenir exactement 16 chiffres.'
                    ]),
                    new Assert\NotBlank([
                        'message' => 'La date d\'expiration ne peut pas être vide.',
//                        'groups' => ['step3']
                    ]),
                ]
            ])
            ->add('expirationDate', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'La date d\'expiration ne peut pas être vide.',
//                        'groups' => ['step3']
                    ]),
                    new Assert\Regex([
                        'pattern' => '/^(0[1-9]|1[0-2])\/\d{2}$/',
//                        'groups' => ['step3']
                    ]),
                    new CardExpirationDate([
                        'message' => 'La date d\'expiration doit être dans le futur.',
//                        'groups' => ['step3']
                    ]),
                ],
            ])
            ->add('cvv', TextType::class, [
                'constraints' => [
                    New Assert\Regex([
                            'pattern' => '/^\d{3}$/',
                            'message' => 'Le code de sécurité doit contenir 3 chiffres.'
                        ]
                    ),
                    new Assert\NotBlank([
                        'message' => 'La date d\'expiration ne peut pas être vide.',
//                        'groups' => ['step3']
                    ]),
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Payment::class,
        ]);
    }
}
