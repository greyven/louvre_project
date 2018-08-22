<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LouvreRoutingTest extends WebTestCase
{
    private $client;

    public function setUp()
    {
        $this->client = static::createClient();
    }

    public function testHomeIsUp()
    {
        $this->client->request('GET', '/');

        $this->assertSame(200, $this->client->getResponse()->getStatusCode());

//        echo $client->getResponse()->getContent();
    }

    public function testCommandIsUp()
    {
        $this->client->request('GET', '/command');

        $this->assertSame(200, $this->client->getResponse()->getStatusCode());

//        echo $client->getResponse()->getContent();
    }
    

    public function testContactIsUp()
    {
        $this->client->request('GET', '/contact');

        $this->assertSame(200, $this->client->getResponse()->getStatusCode());

       // echo $client->getResponse()->getContent();
    }
}