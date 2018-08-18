<?php

namespace Tests\AppBundle\Manager;


use AppBundle\Entity\Command;
use AppBundle\Manager\CommandManager;
use AppBundle\Service\Pay;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Session\Session;

class CommandManagerTest
{
    public function testGetCurrentCommand()
    {
        $command = new Command();
        $command->setId(15);
        $session = new Session();
        $session->set('command', $command);
        $em = new EntityManager();
        $pay = new Pay();
        $swift_mailer = new \Swift_Mailer();

        $cm = new CommandManager($session, $em, $pay, $swift_mailer);

        $commandId = $cm->getCurrentCommand()->getId();

        $this->assertSame(15, $commandId);
    }
}