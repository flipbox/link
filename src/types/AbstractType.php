<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/link/license
 * @link       https://www.flipboxfactory.com/software/link/
 */

namespace flipbox\link\types;

use Craft;
use craft\base\ElementInterface;
use flipbox\link\fields\Link;
use yii\base\Model;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
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
        return Craft::$app->getView()->renderTemplate(
            'link/_components/fieldtypes/Link/types/settings',
            [
                'type' => $this
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function inputHtml(Link $field, TypeInterface $value = null, ElementInterface $element = null): string
    {
        return '';
    }

    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return array_merge(
            parent::attributes(),
            [
                'text'
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(
            parent::attributeLabels(),
            [
                'text' => Craft::t('link', 'Text')
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(
            parent::rules(),
            [
                [
                    [
                        'text'
                    ],
                    'safe',
                    'on' => [
                        Model::SCENARIO_DEFAULT
                    ]
                ]
            ]
        );
    }
}
