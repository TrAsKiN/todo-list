<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use DateTime;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{
    public function testTaskEntity()
    {
        $date = new DateTime();

        $task = new Task;
        $task->setTitle('Task title')
            ->setContent('Task content')
            ->setOwner(null)
            ->setCreatedAt($date)
        ;

        $this->assertEquals('Task title', $task->getTitle());
        $this->assertEquals('Task content', $task->getContent());
        $this->assertEquals(null, $task->getOwner());
        $this->assertEquals($date, $task->getCreatedAt());
    }
}
