<?php

namespace App\Security;

use App\Entity\User;
use App\Enum\UserStatusEnum;
use Symfony\Component\Security\Core\Exception\DisabledException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user): void
    {
        // Si l'utilisateur a un statut "INACTIF" ou "SUPPRIME", lever une exception DisabledException
        if ($user instanceof User && ($user->getStatus() === UserStatusEnum::INACTIF || $user->getStatus() === UserStatusEnum::SUPPRIME)) {
            throw new DisabledException('Votre compte est inactif ou supprimé.');
        }
    }

    public function checkPostAuth(UserInterface $user): void
    {
        
    }
}

