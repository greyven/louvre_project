<?php

namespace Tests\AppBundle\EventSubscriber;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CommandNotFoundSubscriberTest extends WebTestCase
{
    public function testCommandNotFound()
    {
        $client = static::createClient();
        $client->request('GET', '/tickets');
        $client->followRedirect();

        $this->assertContains('Pas de commande en mémoire', $client->getResponse()->getContent());
    }
}