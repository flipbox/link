<?php

namespace flipbox\link\types;

use Craft;
use craft\base\ElementInterface;
use flipbox\link\fields\Link;

class Url extends AbstractType
{
    /**
     * @var
     */
    public $text;

    /**
     * @var
     */
    public $url;

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
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @inheritdoc
     */
    public function getInputHtml(Link $field, $value, ElementInterface $element = null): string
    {
        return Craft::$app->getView()->renderTemplate(
            'link/_components/fieldtypes/Link/input/url',
            [
                'value' => $value,
                'element' => $element,
                'type' => $this,
                'field' => $field
            ]
        );
    }
}
