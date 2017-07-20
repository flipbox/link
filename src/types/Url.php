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
    public $url;

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
