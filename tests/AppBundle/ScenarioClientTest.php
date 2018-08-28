<?php

namespace Tests\AppBundle\Form;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ScenarioClientTest extends WebTestCase
{
    public function testCommandAndBuy()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        // check server response:
        $this->assertTrue($client->getResponse()->isSuccessful());

        $link = $crawler->selectLink('Billetterie')->link();
        $crawler = $client->click($link);

//        COMMAND
        $formCommand = $crawler->selectButton('Valider')->form();
        $formCommand['command[fullDay]'] = 1;
        $formCommand['command[visitDate][day]'] = 13;
        $formCommand['command[visitDate][month]'] = 12;
        $formCommand['command[visitDate][year]'] = 2018;
        $formCommand['command[numberOfTickets]'] = 1;
        $formCommand['command[visitorEmail]'] = 'greyven@gmail.com';

        $client->submit($formCommand);
        $this->assertTrue($client->getResponse()->isRedirection());
        $crawler = $client->followRedirect();

        $this->assertContains('billet', $client->getResponse()->getContent());
        $this->assertContains('journée', $client->getResponse()->getContent());
        $this->assertContains('13/12/2018', $client->getResponse()->getContent());

//        TICKETS
        $formTicketsCollection = $crawler->selectButton('Valider')->form();
        $formTicketsCollection['ticket_form_collection[tickets][0][lastName]'] = 'Séré';
        $formTicketsCollection['ticket_form_collection[tickets][0][firstName]'] = 'Stef';
        $formTicketsCollection['ticket_form_collection[tickets][0][birthDate][day]'] = 4;
        $formTicketsCollection['ticket_form_collection[tickets][0][birthDate][month]'] = 11;
        $formTicketsCollection['ticket_form_collection[tickets][0][birthDate][year]'] = 1983;
        $formTicketsCollection['ticket_form_collection[tickets][0][country]'] = 'FR';
        //$formTicketsCollection['ticket_form_collection[tickets][0][reducedPrice]']->tick();

        $client->submit($formTicketsCollection);
        $this->assertTrue($client->getResponse()->isRedirection());
        $crawler = $client->followRedirect();

        $this->assertContains('16€', $client->getResponse()->getContent());

        //$modal = $crawler->filter('Payer la commande')->form();

    }

    public function testBadFormsEntries()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        // check server response:
        $this->assertTrue($client->getResponse()->isSuccessful());

        $link = $crawler->selectLink('Billetterie')->link();
        $crawler = $client->click($link);

//        COMMAND
        $formCommand = $crawler->selectButton('Valider')->form();
        $formCommand['command[fullDay]'] = 1;
        $formCommand['command[visitDate][day]'] = 28;
        $formCommand['command[visitDate][month]'] = 8;
        $formCommand['command[visitDate][year]'] = 2018;
        $formCommand['command[numberOfTickets]'] = 1;
        $formCommand['command[visitorEmail]'] = 'greyven@gmail';
        $client->submit($formCommand);

        $this->assertTrue($client->getResponse()->isSuccessful());
    }
}