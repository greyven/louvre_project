<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LouvreControllerTest extends WebTestCase
{
    /**
     *
     */
    public function testHomeIsUp()
    {
        $client = static::createClient();
        $client->request('GET', '/home');

        $this->assertSame(200, $client->getResponse()->getStatusCode());

        echo $client->getResponse()->getContent();
    }
}