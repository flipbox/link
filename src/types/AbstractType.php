<?php

namespace flipbox\link\types;

use Craft;
use craft\base\ElementInterface;
use flipbox\link\fields\Link;
use craft\helpers\ArrayHelper;
use yii\base\Model;

abstract class AbstractType extends Model implements TypeInterface
{

    use traits\Base;

    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
        $ref = new \ReflectionClass(static::class);
        return Craft::t('link', $ref->getShortName());
    }

    /**
     * @inheritdoc
     */
    public function settingsHtml(): string
    {
        return '';
    }

    /**
     * @inheritdoc
     */
    public function inputHtml(Link $field, TypeInterface $value = null, ElementInterface $element = null): string
    {
        return '';
    }

//    /**
//     * @param array $attributes
//     * @return string
//     */
//    public function getHtml(array $attributes = []): string
//    {
//        $defaults = [
//            'href' => $this->getUrl(),
//            'title' => $this->getText(),
//        ];
//
//        $text = ArrayHelper::remove($attributes, 'text', $this->getText());
//
//        $properties = array_filter(array_merge(
//            $defaults,
//            $attributes
//        ));
//
//        array_walk($properties, function (&$v, $k) {
//            $v = $k . '="' . $v . '"';
//        });
//
//        return '<a ' . implode(' ', $properties) . '>' . $text . '</a>';
//    }
//
//    /**
//     * @return string
//     */
//    public function __toString()
//    {
//        return $this->getHtml();
//    }
}
