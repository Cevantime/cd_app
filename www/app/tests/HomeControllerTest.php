<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{
    public function testHomePage()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Bienvenue sur la page d\'accueil');

        $a = $crawler->filter('ul li a');

        $titleFirstArticle = $a->text();

        $crawler = $client->click($a->link());

        $this->assertSelectorTextContains('h1', $titleFirstArticle);
    }
}
