<?php

namespace flipbox\link\services;

use flipbox\link\events\RegisterLinkTypes;
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
     * @var TypeInterface[]
     */
    private $types;

    /**
     * @return TypeInterface[]
     */
    public function findAll()
    {
        if ($this->types === null) {
            $this->types = $this->registerTypes();
        }

        return $this->types;
    }

    /**
     * @return TypeInterface[]
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
     * @return TypeInterface[]
     */
    private function firstParty()
    {
        return [
            UrlType::class,
            EntryType::class
        ];
    }
}