<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/link/license
 * @link       https://www.flipboxfactory.com/software/link/
 */

namespace flipbox\link;

use craft\base\Plugin;
use craft\events\RegisterComponentTypesEvent;
use craft\services\Fields;
use flipbox\link\fields\Link as LinkField;
use yii\base\Event;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
class Link extends Plugin
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        // Do parent
        parent::init();

        // Register our fields
        Event::on(
            Fields::class,
            Fields::EVENT_REGISTER_FIELD_TYPES,
            function (RegisterComponentTypesEvent $event) {
                $event->types[] = LinkField::class;
            }
        );
    }

    /*******************************************
     * SERVICES
     *******************************************/

    /**
     * @return services\Type
     */
    public function getType()
    {
        return $this->get('type');
    }
}
