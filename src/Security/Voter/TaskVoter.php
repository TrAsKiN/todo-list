<?php

namespace App\Security\Voter;

use App\Entity\Task;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class TaskVoter extends Voter
{
    public const MY_TASK = 'IS_OWNER';

    protected function supports(string $attribute, $subject): bool
    {
        return $attribute == self::MY_TASK && $subject instanceof Task;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof UserInterface) {
            return false;
        }

        if ($attribute == self::MY_TASK && $subject->getOwner() === $user) {
            return true;
        }

        return false;
    }
}
