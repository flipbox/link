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

}
