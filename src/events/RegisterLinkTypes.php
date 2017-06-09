<?php

namespace flipbox\link\events;

use flipbox\link\types\TypeInterface;
use yii\base\Event;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
class RegisterLinkTypes extends Event
{
    /**
     * @var TypeInterface[]
     */
    public $types = [];
}
