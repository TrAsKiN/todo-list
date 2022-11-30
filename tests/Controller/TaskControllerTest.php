<?php

namespace App\Tests\Controller;

use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Form;

class TaskControllerTest extends WebTestCase
{
    public function testListWhenNotLoggedIn(): void
    {
        $client = static::createClient();
        $client->request('GET', '/tasks');

        $this->assertResponseRedirects();
        $client->followRedirect();
        $this->assertRouteSame('login');
    }

    public function testListWhenLoggedInAsUser(): void
    {
        $client = static::createClient();
        SecurityControllerTest::login($client, 'user');
        $client->request('GET', '/tasks');

        $this->assertResponseIsSuccessful();
        $this->assertRouteSame('task_list');
        $this->assertSelectorNotExists('h2:nth-of-type(2)');
    }

    public function testListWhenLoggedInAsAdmin(): void
    {
        $client = static::createClient();
        SecurityControllerTest::login($client, 'admin');
        $client->request('GET', '/tasks');

        $this->assertResponseIsSuccessful();
        $this->assertRouteSame('task_list');
        $this->assertSelectorExists('h2:nth-of-type(2)');
        $this->assertSelectorTextContains('h2:nth-of-type(2)', 'Tâches anonyme');
    }

    public function testCreateWhenNotLoggedIn(): void
    {
        $client = static::createClient();
        $client->request('GET', '/tasks/create');

        $this->assertResponseRedirects();
        $client->followRedirect();
        $this->assertRouteSame('login');
    }

    public function testCreateWhenLoggedIn(): void
    {
        $client = static::createClient();
        SecurityControllerTest::login($client, 'user');
        $crawler = $client->request('GET', '/tasks/create');

        $this->assertResponseIsSuccessful();
        $this->assertRouteSame('task_create');
        $this->assertInstanceOf(Form::class, $crawler->selectButton('Ajouter')->form());
    }

    public function testCreateTaskSuccessfully(): void
    {
        $client = static::createClient();
        SecurityControllerTest::login($client, 'user');
        $client->request('GET', '/tasks/create');

        $client->submitForm('Ajouter', [
            'task[title]' => 'Task title',
            'task[content]' => 'My task content',
        ]);

        $this->assertResponseRedirects();
        $crawler = $client->followRedirect();
        $this->assertRouteSame('task_list');
        $this->assertSelectorExists('div.alert.alert-success');
        $this->assertCount(1, $crawler->filter('.task'));
    }

    public function testCreateTaskWithoutTitle(): void
    {
        $client = static::createClient();
        SecurityControllerTest::login($client, 'user');
        $client->request('GET', '/tasks/create');

        $client->submitForm('Ajouter', [
            'task[content]' => 'My task content',
        ]);

        $this->assertSelectorExists('.has-error #task_title');
        $this->assertSelectorTextContains('.help-block li', 'Vous devez saisir un titre.');
    }

    public function testCreateTaskWithoutContent(): void
    {
        $client = static::createClient();
        SecurityControllerTest::login($client, 'user');
        $client->request('GET', '/tasks/create');

        $client->submitForm('Ajouter', [
            'task[title]' => 'Task title',
        ]);

        $this->assertSelectorExists('.has-error #task_content');
        $this->assertSelectorTextContains('.help-block li', 'Vous devez saisir du contenu.');
    }

    public function testEditTaskWhenNotLoggedIn(): void
    {
        $client = static::createClient();
        $user = static::getContainer()->get(UserRepository::class)->findOneByUsername('user');
        $task = static::getContainer()->get(TaskRepository::class)->findOneBy(['owner' => $user]);
        $client->request('GET', '/tasks/'. $task->getId() .'/edit');

        $this->assertResponseRedirects();
        $client->followRedirect();
        $this->assertRouteSame('login');
    }

    public function testEditTaskSuccessfully(): void
    {
        $client = static::createClient();
        $user = SecurityControllerTest::login($client, 'user');
        $task = static::getContainer()->get(TaskRepository::class)->findOneBy(['owner' => $user]);
        $client->request('GET', '/tasks/'. $task->getId() .'/edit');

        $this->assertResponseIsSuccessful();
        $this->assertRouteSame('task_edit');
        $this->assertRequestAttributeValueSame('id', $task->getId());
        $this->assertInputValueSame('task[title]', $task->getTitle());
        $this->assertSelectorTextSame('textarea[name="task[content]"]', $task->getContent());

        $client->submitForm('Modifier', [
            'task[title]' => 'New title',
            'task[content]' => 'New content',
        ]);

        $this->assertResponseRedirects();
        $crawler = $client->followRedirect();
        $this->assertResponseIsSuccessful();
        $this->assertRouteSame('task_list');
        $this->assertCount(1, $crawler->filter('.task'));
    }

    public function testEditTaskWhenNotTheOwner(): void
    {
        $client = static::createClient();
        $user = static::getContainer()->get(UserRepository::class)->findOneByUsername('user');
        SecurityControllerTest::login($client, 'admin');
        $task = static::getContainer()->get(TaskRepository::class)->findOneBy(['owner' => $user]);
        $client->request('GET', '/tasks/'. $task->getId() .'/edit');

        $this->assertResponseStatusCodeSame(403);
    }

    public function testTaskToggleSuccessfully(): void
    {
        $client = static::createClient();
        $user = SecurityControllerTest::login($client, 'user');
        $task = static::getContainer()->get(TaskRepository::class)->findOneBy(['owner' => $user]);
        $client->request('GET', '/tasks/'. $task->getId() .'/toggle');

        $this->assertResponseRedirects();
        $crawler = $client->followRedirect();
        $this->assertRouteSame('task_list');
        $this->assertSelectorExists('div.alert.alert-success');
        $this->assertCount(1, $crawler->filter('.task .glyphicon-ok'));
    }

    public function testTaskToggleWhenNotTheOwner(): void
    {
        $client = static::createClient();
        $user = static::getContainer()->get(UserRepository::class)->findOneByUsername('user');
        SecurityControllerTest::login($client, 'admin');
        $task = static::getContainer()->get(TaskRepository::class)->findOneBy(['owner' => $user]);
        $client->request('GET', '/tasks/'. $task->getId() .'/toggle');

        $this->assertResponseStatusCodeSame(403);
    }
}
