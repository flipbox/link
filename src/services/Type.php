<?php

namespace flipbox\link\services;

use craft\helpers\ArrayHelper;
use flipbox\link\events\RegisterLinkTypes;
use flipbox\link\types\Asset as AssetType;
use flipbox\link\types\Entry as EntryType;
use flipbox\link\types\TypeInterface;
use flipbox\link\types\Url as UrlType;
use yii\base\Component;

class Type extends Component
{

    /**
     * The event name
     */
    const EVENT_REGISTER_TYPES = 'registerTypes';

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
     * @param TypeInterface $type
     * @param array $properties
     */
    public function populate(TypeInterface $type, array $properties)
    {
        foreach ($type->getProperties() as $key => $value) {
            if(array_key_exists($key, $properties)) {
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
     * @return array
     */
    private function firstParty()
    {
        return [
            AssetType::class,
            EntryType::class,
            UrlType::class

        ];
    }
}
