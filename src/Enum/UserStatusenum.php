<?php

namespace App\Enum;

enum UserStatusenum: string
{
    case ACTIF = 'actif';                   // L'utilisateur a accès à toutes les fonctionnalités
    case INACTIF = 'inactif';                   // Compte créé mais n'a pas encore validé son email
    case BANNI = 'banni';                   // L'utilisateur est interdit d'accès de façon permanente mais peut etre debanni par un admin
    case SUPPRIME = 'supprimé';             // Ne peux etre reactivé car supprimé suite a demande de l'utilisateur
}
