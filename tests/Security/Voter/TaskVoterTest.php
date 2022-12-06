<?php

namespace App\Tests\Security\Voter;

use App\Entity\Task;
use App\Security\Voter\TaskVoter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

class TaskVoterTest extends TestCase
{
    public function testVoteOnAttributeWhenNoUser(): void
    {
        $token = $this->createMock(TokenInterface::class);
        $token->expects($this->once())
            ->method('getUser')
            ->willReturn(null);

        $voter = new TaskVoter;
        $this->assertEquals(VoterInterface::ACCESS_DENIED, $voter->vote($token, new Task, [TaskVoter::MY_TASK]));
    }
}
