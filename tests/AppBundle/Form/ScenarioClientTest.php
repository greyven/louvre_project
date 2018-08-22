<?php

namespace Tests\AppBundle\Form;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ScenarioClientTest extends WebTestCase
{
    public function testCommand()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        // check server response:
        $this->assertTrue($client->getResponse()->isSuccessful());

        $link = $crawler->selectLink('Billetterie')->link();
        $crawler = $client->click($link);

        $form = $crawler->selectButton('Valider')->form();
        $form['command[fullDay]'] = 0;
        $form['command[visitDate][day]'] = 13;
        $form['command[visitDate][month]'] = 8;
        $form['command[visitDate][year]'] = 2018;
        $form['command[numberOfTickets]'] = 1;
        $form['command[visitorEmail]'] = 'greyven@gmail.com';

        $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirection());
        $client->followRedirect();

        $this->assertContains('Vous souhaitez réserver 1 billet demi-journée pour le 13/8/2018',
                              $client->getResponse()->getContent())
        ;

    }
}