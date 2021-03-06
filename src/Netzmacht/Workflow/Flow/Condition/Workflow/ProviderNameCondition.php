<?php

/**
 * @package    workflow
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2014 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
 */

namespace Netzmacht\Workflow\Flow\Condition\Workflow;

use Netzmacht\Workflow\Data\EntityId;
use Netzmacht\Workflow\Flow\Workflow;

/**
 * Class ProviderTypeCondition check if entity matches a specific provider.
 *
 * @package Netzmacht\Workflow\Flow\Workflow\Condition
 */
class ProviderNameCondition implements Condition
{
    /**
     * Provider name to check against.
     *
     * @var string
     */
    private $providerName;

    /**
     * Get the provider name to check against.
     *
     * @return string
     */
    public function getProviderName()
    {
        return $this->providerName;
    }

    /**
     * Set a new provider name.
     *
     * @param string $providerName New provider name.
     *
     * @return $this
     */
    public function setProviderName($providerName)
    {
        $this->providerName = $providerName;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function match(Workflow $workflow, EntityId $entityId, $entity)
    {
        if ($this->providerName) {
            return $entityId->getProviderName() == $this->providerName;
        }

        return $entityId->getProviderName() == $workflow->getProviderName();
    }
}
