<?php

/**
 * @package    workflow
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2014 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
 */

namespace Netzmacht\Workflow;

use Netzmacht\Workflow\Factory\Event\CreateFormEvent;
use Netzmacht\Workflow\Factory\Event\CreateManagerEvent;
use Netzmacht\Workflow\Form\Form;
use Netzmacht\Workflow\Manager\Manager;
use RuntimeException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface as EventDispatcher;

/**
 * Class Factory dispatches events which the implementation can subscribe to create the instances.
 *
 * @package Netzmacht\Workflow
 */
class Factory
{
    /**
     * The event dispatcher.
     *
     * @var EventDispatcher
     */
    private $eventDispatcher;

    /**
     * Construct.
     *
     * @param EventDispatcher $eventDispatcher The event dispatcher.
     */
    public function __construct(EventDispatcher $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * Create a workflow manager.
     *
     * @param string      $providerName The provider name. Typically a database table name.
     * @param string|null $type         Optional workflow type limitation. Is passed to the factory event.
     *
     * @return Manager
     *
     * @throws RuntimeException If manager was not created.
     */
    public function createManager($providerName, $type = null)
    {
        $event = new CreateManagerEvent($providerName, $type);
        $this->eventDispatcher->dispatch($event::NAME, $event);

        return $this->guardCreated(
            $event->getManager(),
            sprintf('Could not create manager for provider "%s" and type "%s" ', $providerName, $type)
        );
    }

    /**
     * Create a form.
     *
     * @param string $type The form type.
     * @param string $name The form name.
     *
     * @return Form
     *
     * @throws RuntimeException If form was not created.
     */
    public function createForm($type, $name)
    {
        $event = new CreateFormEvent($type, $name);
        $this->eventDispatcher->dispatch($event::NAME, $event);

        return $this->guardCreated($event->getForm(), sprintf('Could not create form type "%s"', $type));
    }

    /**
     * Guard that result was created.
     *
     * @param mixed  $result  Result of the event dispatched factory.
     * @param string $message The error message.
     *
     * @return mixed
     *
     * @throws RuntimeException If result was not created.
     */
    private function guardCreated($result, $message)
    {
        if (!$result) {
            throw new RuntimeException($message);
        }

        return $result;
    }
}
