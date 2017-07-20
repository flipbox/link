<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/link/license
 * @link       https://www.flipboxfactory.com/software/link/
 */

namespace flipbox\link\types\traits;

use craft\helpers\ArrayHelper;
use craft\helpers\StringHelper;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
trait Base
{

    /**
     * @var string
     */
    public $text;

    /**
     * @var string
     */
    protected $identifier;

    /**
     * @return string
     */
    abstract public function getUrl(): string;

    /**
     * Returns the list of attribute names.
     * By default, this method returns all public non-static properties of the class.
     * You may override this method to change the default behavior.
     * @return array list of attribute names.
     */
    abstract protected function attributes();

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        if ($this->identifier === null) {
            $this->identifier = StringHelper::randomString();
        }
        return $this->identifier;
    }

    /**
     * @param string $identifier
     * @return string
     */
    public function setIdentifier(string $identifier): string
    {
        $this->identifier = $identifier;
        return $this;
    }

    /**
     * @return array
     */
    public function properties(): array
    {
        return array_diff($this->attributes(), $this->settings());
    }

    /**
     * @return array
     */
    public function getProperties(): array
    {
        $properties = [];

        foreach ($this->properties() as $property) {
            $properties[$property] = $this->$property;
        }

        return $properties;
    }

    /**
     * @inheritdoc
     */
    public function settings(): array
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function getSettings(): array
    {
        $settings = [];

        foreach ($this->settings() as $attribute) {
            $settings[$attribute] = $this->$attribute;
        }

        return $settings;
    }

    /**
     * @param array $attributes
     * @return string
     */
    public function getHtml(array $attributes = []): string
    {
        $defaults = [
            'href' => $this->getUrl(),
            'title' => $this->getText(),
        ];

        $text = ArrayHelper::remove($attributes, 'text', $this->getText());

        $properties = array_filter(array_merge(
            $defaults,
            $attributes
        ));

        array_walk($properties, function (&$v, $k) {
            $v = $k . '="' . $v . '"';
        });

        return '<a ' . implode(' ', $properties) . '>' . $text . '</a>';
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getHtml();
    }
}
