<?php

namespace App\Service;

use App\Entity\UserOnboarding;
use App\Form\AddressInformationType;
use App\Form\PaymentInformationType;
use App\Form\UserOnboardingType;

class OnboardingFlowManager
{
    public function getFormTypeForStep(int $step, UserOnboarding $data): ?string
    {
        return match ($step) {
            1 => UserOnboardingType::class,
            2 => AddressInformationType::class,
            3 => $data->getSubscriptionType() === 'premium' ? PaymentInformationType::class : null,
            default => null,
        };
    }

    public function getNextStep(int $step, UserOnboarding $data): int
    {
        if ($step === 2 && $data->getSubscriptionType() === 'free') {
            return 4;
        }
        return $step + 1;
    }
}
