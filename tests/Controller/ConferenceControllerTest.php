<?php

namespace App\Tests\Controller;

use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class ConferenceControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Give your feedback');
    }

    public function testConferencePage()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertCount(2, $crawler->filter('h4'));

        $client->clickLink('View');

        $this->assertPageTitleContains('Canterlot');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Canterlot 2019');
        $this->assertSelectorExists('div:contains("There are 1 comments")');
    }

    public function testCommentSubmission()
    {
        $client = static::createClient();
        //OK (3 tests, 13 assertions)
        $client->request('GET', '/login');
        $client->submitForm('Sign in', [
            'username' => 'admin',
            'password' => 'admin'
        ]);
        $this->assertResponseRedirects();
        $client->followRedirect();

        $client->request('GET', '/conference/canterlot-2019');
        $client->submitForm('Submit', [
            'comment_form[author]' => 'Derpy Hooves',
            'comment_form[text]' => 'derp',
            'comment_form[email]' => $email = 'me@automat.ed',
            'comment_form[photo]' => dirname(__DIR__, 2).'/public/images/0rq1YhNFzAU.jpg',
        ]);
        $this->assertResponseRedirects();

        $comment = self::$container->get(CommentRepository::class)->findOneByEmail($email);
        $comment->setStatus('published');
        self::$container->get(EntityManagerInterface::class)->flush();

        $client->followRedirect();
        $this->assertSelectorExists('div:contains("There are 2 comments")');
    }
}
