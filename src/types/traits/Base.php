<?php

namespace flipbox\link\types\traits;

use craft\helpers\ArrayHelper;

trait Base
{

    /**
     * @var string
     */
    public $text;

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
