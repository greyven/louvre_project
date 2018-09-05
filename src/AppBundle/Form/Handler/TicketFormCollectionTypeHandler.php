<?php

namespace AppBundle\Form\Handler;


use AppBundle\Entity\Command;
use AppBundle\Form\TicketFormCollectionType;
use AppBundle\Manager\CommandManager;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class TicketFormCollectionTypeHandler
{
    /**
     * @var RequestStack
     */
    private $stack;
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    public function __construct(RequestStack $stack, FormFactoryInterface $formFactory)
    {

        $this->stack = $stack;
        $this->formFactory = $formFactory;
    }

    public function handle(Command $command, CommandManager $commandManager)
    {
        $request = $this->stack->getCurrentRequest();

        $ticketsCollection = $this->formFactory->create(TicketFormCollectionType::class, $command);

        $ticketsCollection->handleRequest($request);

        if ($ticketsCollection->isSubmitted() && $ticketsCollection->isValid())
        {
            $commandManager->completeCommand($command);
            return true;
        }

        return $ticketsCollection;
    }
}