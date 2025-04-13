<?php

namespace App\Service;

use App\Entity\UserOnboarding;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class OnboardingSessionStorage
{
    public function get(SessionInterface $session): UserOnboarding
    {

        return $session->get('onboarding') ?? new UserOnboarding();
    }

    public function set(SessionInterface $session, UserOnboarding $user): void
    {
        $session->set('onboarding', $user);
    }

    public function clear(SessionInterface $session): void
    {
        $session->remove('onboarding');
    }
}