<?php

namespace App\Tests\Controller;

use App\Tests\NeedLogin;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class PageControllerTest extends WebTestCase
{
    use NeedLogin;
    use FixturesTrait;
    public function testHelloPage() {
        $client = static::createClient();
        $client->request('GET', '/hello');

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testH1HelloPage() {
        $client = static::createClient();
        $client->request('GET', '/hello');

        $this->assertSelectorTextContains('h1', 'Bienvenue sur mon site');
    }

    public function testAuthPageIsResctricted() {
        $client = static::createClient();
        $client->request('GET', '/auth');

        $this->assertResponseRedirects('/login');

    }

    public function testRedirectToLogin() {
        $client = static::createClient();
        $client->request('GET', '/auth');

        $this->assertResponseRedirects('/login');
    }

    public function testLetAuthenticatedUserAccessAuth() {
        $client = static::createClient();
        $users = $this->loadFixtureFiles([__DIR__ . '/users.yaml']);
        $this->login($client,$users['user_user']);
        $client->request('GET', '/auth');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
    
    public function testAdminRequiredAdminRole() {
        $client = static::createClient();
        $users = $this->loadFixtureFiles([__DIR__ . '/users.yaml']);
        $this->login($client, $users['user_user']);
        $client->request('GET', '/admin');
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testAdminRequiredAdminRoleWithSufficientRole() {
        $client = static::createClient();
        $users = $this->loadFixtureFiles([__DIR__ . '/users.yaml']);
        $this->login($client, $users['user_admin']);
        $client->request('GET', '/admin');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testMailSendEmail() {
        $client = static::createClient();
        $client->enableProfiler();

        $client->request('GET', '/mail');

        /** @var DataCollectorInterface $mailCollector */
        $mailCollector = $client->getProfile()->getCollector('swiftmailer');

        $this->assertEquals(1, $mailCollector->getMessageCount());

        /** @var \Swift_Message[] $messages */
        $messages = $mailCollector->getMessages();

        $this->assertEquals($messages[0]->getTo(), ['contact@doe.fr' => null]);
    }
}