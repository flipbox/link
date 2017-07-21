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

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
class Url extends AbstractType
{
    /**
     * @var
     */
    public $url;

    /**
     * @var string
     */
    protected $identifier = 'url';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->identifier = 'url';
    }

    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
        return Craft::t('link', 'Url');
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url ?: '';
    }

    /**
     * @inheritdoc
     */
    public function inputHtml(Link $field, TypeInterface $type = null, ElementInterface $element = null): string
    {
        return Craft::$app->getView()->renderTemplate(
            'link/_components/fieldtypes/Link/input/url',
            [
                'value' => $type,
                'element' => $element,
                'type' => $this,
                'field' => $field
            ]
        );
    }
}
