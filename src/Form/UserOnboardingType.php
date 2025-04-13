<?php

namespace App\Form;

use App\Entity\UserOnboarding;
use App\Repository\UserOnboardingRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Context\ExecutionContextInterface;


class UserOnboardingType extends AbstractType
{

    public function __construct(private readonly UserOnboardingRepository $repository){}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'The name cannot be empty.']),
                    new Assert\Length([
                        'min' => 2,
                        'minMessage' => 'Le nom doit comporter au moins {{ limit }} caractères.',
                    ])
                ]
            ])
            ->add('email', EmailType::class, [
                'constraints' => [
                    new Assert\Callback([$this, 'validateUniqueEmail'])
                ]
            ])
            ->add('phone', TelType::class, [
                'constraints' => [
                    new Assert\Length([
                        'min' => 10,
                        'max' => 15,
                        'minMessage' => 'The phone number must contain at least {{ limit }} characters.',
                        'maxMessage' => 'The phone number must not exceed {{ limit }} characters.'
                    ]),
                    new Assert\Regex([
                        'pattern' => '/^\+?[0-9]{10,15}$/',
                        'message' => 'Please enter a valid phone number (e.g., +33123456789).'
                    ])
                ]
            ])
            ->add('subscriptionType', ChoiceType::class, [
                'choices' => [
                    'Select' => null,
                    'Free' => 'free',
                    'Premium' => 'premium',
                ],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'The subscription type cannot be empty.']),
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserOnboarding::class,
        ]);
    }

    public function validateUniqueEmail($value, ExecutionContextInterface $context)
    {
        // Vérifier si l'email existe déjà dans la base de données
        $existingUser = $this->repository->findOneByEmail($value);

        if ($existingUser) {
            // Si l'email existe déjà, ajouter une violation
            $context->buildViolation('The email {{ value }} is already in use.')
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
