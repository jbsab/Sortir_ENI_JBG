<?php

namespace App\Security;

use App\Entity\Participant as Participant;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
class UserChecker implements UserCheckerInterface
{

    public function checkPreAuth(UserInterface $user)
    {
        if (!$user instanceof Participant) {
            return;
        }

        if (!$user->isActif()) {
            throw new CustomUserMessageAccountStatusException('Votre compte est inactif');

        }

    }

    public function checkPostAuth(UserInterface $user)
    {
        if (!$user instanceof Participant) {
            return;
        }


    }
}