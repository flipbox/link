<?php

namespace flipbox\link\types;

use Craft;
use craft\base\ElementInterface;
use craft\elements\Asset as AssetElement;
use craft\fields\Assets;

/**
 * @method AssetElement findElement()
 */
class Asset extends Assets implements TypeInterface
{

    use traits\Element;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->applyDefaultProperties();
    }

    /**
     * @inheritdoc
     */
    public function getElementText(): string
    {
        if(!$element = $this->findElement()) {
            return '';
        }
        return $element->title;
    }

    /**
     * @inheritdoc
     */
    public function getUrl(): string
    {
        if(!$element = $this->findElement()) {
            return '';
        }
        return (string) $element->getUrl();
    }

    /**
     * @inheritdoc
     */
    protected function lookupElementById(int $id)
    {
        return Craft::$app->getAssets()->getAssetById($id);
    }

    /**
     * @inheritdoc
     */
    protected function inputTemplateVariables($value = null, ElementInterface $element = null): array
    {
        return parent::inputTemplateVariables([$this->findElement()], $element);
    }
}
