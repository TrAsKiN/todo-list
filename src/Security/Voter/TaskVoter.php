<?php

namespace App\Security\Voter;

use App\Entity\Task;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class TaskVoter extends Voter
{
    public const MY_TASK = 'IS_OWNER';
    public const ANONYMOUS_TASK = 'ANONYMOUS_TASK';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::MY_TASK, self::ANONYMOUS_TASK]) && $subject instanceof Task;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof UserInterface) {
            return false;
        }

        return match ($attribute) {
            self::MY_TASK => $subject->getOwner() === $user,
            self::ANONYMOUS_TASK => $subject->getOwner() === null && in_array('ROLE_ADMIN', $user->getRoles()),
        };
    }
}
