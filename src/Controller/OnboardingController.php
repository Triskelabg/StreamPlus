<?php

namespace App\Controller;

use App\Entity\UserOnboarding;
use App\Service\OnboardingFlowManager;
use App\Service\OnboardingSessionStorage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

final class OnboardingController extends AbstractController
{
    #[Route('/onboarding/step/{step}', name: 'onboarding_step')]
    public function step(
        int $step,
        Request $request,
        SessionInterface $session,
        OnboardingSessionStorage $storage,
        OnboardingFlowManager $flowManager,
    ): Response {
        $user = $storage->get($session);

        $formClass = $flowManager->getFormTypeForStep($step, $user);

        if ($step === 4) {
            return $this->render('onboarding/confirm.html.twig', ['user' => $user]);
        }

        if (!$formClass) {
            return $this->redirectToRoute('onboarding_step', ['step' => $flowManager->getNextStep($step, $user)]);
        }

        $form = $this->createForm($formClass, $user, [
//            'validation_groups' => ['step' . $step]
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $storage->set($session, $user);

            return $this->redirectToRoute('onboarding_step', [
                'step' => $flowManager->getNextStep($step, $user),
            ]);
        }

        return $this->render('onboarding/step.html.twig', [
            'form' => $form,
            'step' => $step,
        ]);
    }

    #[Route('/onboarding/submit', name: 'onboarding_submit')]
    public function submit(
        SessionInterface $session,
        EntityManagerInterface $em,
        OnboardingSessionStorage $storage
    ): Response {
        $user = $storage->get($session);
        if (!$user instanceof UserOnboarding) {
            return $this->redirectToRoute('onboarding_step', ['step' => 1]);
        }
        if($user->getName()){
            $em->persist($user);
            $em->flush();
            $storage->clear($session);

            $this->addFlash("success", 'votre subscription a bien ete prise en compte');
            return $this->render('onboarding/success.html.twig');
        }
        return $this->render('onboarding/success.html.twig');
    }
}
