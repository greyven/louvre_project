<?php

namespace AppBundle\Form\Handler;


use AppBundle\Entity\Command;
use AppBundle\Form\CommandType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class CommandTypeHandler
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

    public function handle(Command $command)
    {
        $request = $this->stack->getCurrentRequest();

        $commandForm = $this->formFactory->create(CommandType::class, $command);

        $commandForm->handleRequest($request);

        if ($commandForm->isSubmitted() && $commandForm->isValid())
        {
            return $command;
        }

        return $commandForm;
    }
}