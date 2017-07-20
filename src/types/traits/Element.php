<?php

namespace flipbox\link\types\traits;

use Craft;
use craft\base\ElementInterface;
use flipbox\link\fields\Link;
use flipbox\link\types\TypeInterface;
use yii\base\Exception;

trait Element
{
    use Base;

    /**
     * @inheritdoc
     */
    protected function applyDefaultProperties()
    {
        $this->handle = 'elementId';
        $this->id = static::class;
        $this->allowLimit = true;
        $this->limit = 1;
    }

    /**
     * @var bool
     */
    public $overrideText = true;

    /**
     * @var int
     */
    protected $elementId;

    /**
     * @var ElementInterface
     */
    protected $element;

    /**
     * @return string
     */
    abstract protected function getElementText(): string;

    /**
     * @param int $id
     * @return ElementInterface|null
     */
    abstract protected function lookupElementById(int $id);

    /**
     * @return int
     */
    public function getElementId()
    {
        return $this->elementId;
    }

    /**
     * @return ElementInterface|null
     */
    protected function findElement()
    {
        if ($this->element === null) {
            $this->element = $this->lookupElement() ?: false;
        }

        return $this->element === false ? null : $this->element;
    }

    /**
     * @return ElementInterface|null
     */
    protected function lookupElement()
    {
        if ($this->elementId === null) {
            return null;
        }

        return $this->lookupElementById($this->elementId);
    }

    /**
     * @return array
     */
    public function properties(): array
    {
        return [
            'elementId',
            'text'
        ];
    }

    /**
     * @param $elementId
     */
    public function setElementId($elementId)
    {
        if (is_array($elementId)) {
            $elementId = reset($elementId);
        }
        $this->elementId = (int)$elementId;
    }

    /**
     * @inheritdoc
     */
    public function inputHtml(Link $field, TypeInterface $type = null, ElementInterface $element = null): string
    {
        return Craft::$app->getView()->renderTemplate(
            'link/_components/fieldtypes/Link/input/element',
            [
                'value' => $type,
                'field' => $field,
                'type' => $this,
                'elementSelectInput' => $this->getInputHtml($type, $element)
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function settingsHtml(): string
    {
        return Craft::$app->getView()->renderTemplate(
            'link/_components/fieldtypes/Link/settings/element',
            [
                'type' => $this,
                'elementSelectSettings' => $this->getSettingsHtml()
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function getText(): string
    {
        if ($this->text !== null) {
            return $this->text;
        }
        return $this->getElementText();
    }
}
