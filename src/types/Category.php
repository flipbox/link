<?php

namespace flipbox\link\types;

use Craft;
use craft\base\ElementInterface;
use craft\elements\Asset as AssetElement;
use craft\fields\Categories;
use craft\helpers\ArrayHelper;

/**
 * @method AssetElement findElement()
 */
class Category extends Categories implements TypeInterface
{

    use traits\Element;

    public function getElements()
    {
        return null;
    }

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
        return Craft::$app->getCategories()->getCategoryById($id);
    }

    /**
     * @inheritdoc
     */
    protected function inputTemplateVariables($value = null, ElementInterface $element = null): array
    {
        return parent::inputTemplateVariables(
            $this->findElement() ? [$this->findElement()] : null,
            $element
        );
    }

    /**
     * @inheritdoc
     */
    public function settings(): array
    {
        // Get setting attributes from component
        $settings = $this->settingsAttributes();

        // Remove the public 'text' attribute (it's not a setting)
        ArrayHelper::removeValue($settings, 'text');

        return $settings;
    }
}
