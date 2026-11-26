<?php

namespace App\Security\Voter;

use App\Entity\Tarea;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

final class TareaVoter extends Voter
{
    public const VER = 'TAREA_VER';
    public const EDITAR = 'TAREA_EDITAR';
    public const ELIMINAR = 'TAREA_ELIMINAR';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::VER, self::EDITAR, self::ELIMINAR], true)
            && $subject instanceof Tarea;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        return $subject->getPropietario()?->getId() === $user->getId();
    }
}