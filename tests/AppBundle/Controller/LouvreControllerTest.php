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

//        echo $client->getResponse()->getContent();
    }

    public function testCommandIsUp()
    {
        $client = static::createClient();
        $client->request('GET', '/command');

        $this->assertSame(200, $client->getResponse()->getStatusCode());

//        echo $client->getResponse()->getContent();
    }
    

    public function testContactIsUp()
    {
        $client = static::createClient();
        $client->request('GET', '/contact');

        $this->assertSame(200, $client->getResponse()->getStatusCode());

       // echo $client->getResponse()->getContent();
    }

    public function testCommand()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/home');

        $link = $crawler->selectLink('Billetterie')->link();
        $crawler = $client->click($link);

        $form = $crawler->selectButton('Valider')->form();
        $form['Commande_:[fullDay]'] = 0;
        $form['Commande_:[visitDate][day]'] = 13;
        $form['Commande_:[visitDate][month]'] = 8;
        $form['Commande_:[visitDate][year]'] = 2018;
        $form['Commande_:[numberOfTickets]'] = 1;
        $form['Commande_:[visitorEmail]'] = 'greyven@gmail.com';

        $client->submit($form);
//        $client->followRedirect();
    }
}