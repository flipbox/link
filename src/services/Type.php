<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/link/license
 * @link       https://www.flipboxfactory.com/software/link/
 */

namespace flipbox\link\services;

use craft\helpers\ArrayHelper;
use flipbox\link\events\RegisterLinkTypes;
use flipbox\link\types\Asset as AssetType;
use flipbox\link\types\Category as CategoryType;
use flipbox\link\types\Entry as EntryType;
use flipbox\link\types\TypeInterface;
use flipbox\link\types\Url as UrlType;
use yii\base\Component;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
class Type extends Component
{

    /**
     * The event name
     */
    const EVENT_REGISTER_TYPES = 'registerTypes';

    /**
     * The property to uniquely identify a link type
     */
    const IDENTIFIER = 'identifier';

    /**
     * @var array
     */
    private $types;

    /**
     * @return array
     */
    public function findAll()
    {
        if ($this->types === null) {
            $this->types = $this->registerTypes();
        }

        return $this->types;
    }

    /**
     * @param string $class
     * @return TypeInterface|null
     */
    public function find(string $class)
    {
        return ArrayHelper::getValue(
            $this->findAll(),
            $class
        );
    }

    /**
     * @return array
     */
    protected function registerTypes()
    {
        $event = new RegisterLinkTypes([
            'types' => $this->firstParty()
        ]);

        $this->trigger(
            self::EVENT_REGISTER_TYPES,
            $event
        );

        return $this->resolveTypes($event->types);
    }

    /**
     * Populate valid properties.  This occurs when we have a content value
     * and we need to populate it's contents on an existing TypeInterface
     *
     * @param TypeInterface $type
     * @param array $properties
     */
    public function populate(TypeInterface $type, array $properties)
    {
        foreach ($type->getProperties() as $key => $value) {
            if (array_key_exists($key, $properties)) {
                $type->{$key} = $properties[$key];
            }
        }
    }

    /**
     * @param array $types
     * @return array
     */
    private function resolveTypes(array $types)
    {
        $validTypes = [];
        foreach ($types as $type) {
            if (!$type instanceof TypeInterface) {
                $type = new $type();
            }
            $validTypes[get_class($type)] = $type;
        }

        return $validTypes;
    }

    /**
     * @param $type
     * @return array|null|object
     */
    public function create($type)
    {
        if ($type instanceof TypeInterface) {
            return $type;
        }

        if (!is_array($type)) {
            $type = ['class' => $type];
        }

        $type = \Yii::createObject(
            $type
        );

        if (!$type instanceof TypeInterface) {
            return null;
        }

        return $type;
    }

    /**
     * @return array
     */
    private function firstParty()
    {
        return [
            AssetType::class,
            CategoryType::class,
            EntryType::class,
            UrlType::class
        ];
    }
}
