<?php

namespace flipbox\link\types;

use Craft;
use craft\base\ElementInterface;
use flipbox\link\fields\Link;
use flipbox\spark\helpers\ArrayHelper;
use yii\base\Model;

abstract class AbstractType extends Model implements TypeInterface
{

    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
        $ref = new \ReflectionClass(static::class);
        return Craft::t('link', $ref->getShortName());
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
     * @inheritdoc
     */
    public function getSettingsHtml(): string
    {
        return '';
    }

    /**
     * @inheritdoc
     */
    public function getInputHtml(Link $field, $value, ElementInterface $element = null): string
    {
        return '';
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