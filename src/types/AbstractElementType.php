<?php

namespace flipbox\link\types;

use Craft;
use craft\base\Element;
use craft\base\ElementInterface;
use craft\elements\db\ElementQueryInterface;
use flipbox\link\fields\Link;
use craft\helpers\ArrayHelper;
use yii\base\Exception;
use yii\base\Model;
use craft\validators\ArrayValidator;

abstract class AbstractElementType extends AbstractType
{
    /**
     * @var bool
     */
    public $overrideText = true;

    /**
     * @var string
     */
    public $text;

    /**
     * @var string|string[]|null The source keys that this field can relate elements from (used if [[allowMultipleSources]] is set to true)
     */
    public $sources = '*';

    /**
     * @var string|null The source key that this field can relate elements from (used if [[allowMultipleSources]] is set to false)
     */
    public $source;

    /**
     * @var int|null The site that this field should relate elements from
     */
    public $targetSiteId;

    /**
     * @var bool Whether to allow multiple source selection in the settings
     */
    public $allowMultipleSources = true;

    /**
     * @inheritdoc
     */
    public $label = 'Select Element';

    /**
     * @var string|null The view mode
     */
    public $viewMode;

    /**
     * @var string|null The JS class that should be initialized for the input
     */
    protected $inputJsClass;

    /**
     * @var int
     */
    protected $id;

    /**
     * @var ElementInterface
     */
    protected $element;

    /**
     * @return array
     */
    public function attributes(): array
    {
        return array_merge(
            parent::attributes(),
            [
                'id'
            ]
        );
    }

    /**
     * @return array
     */
    public function settings(): array
    {
        return [
            'sections',
            'label',
            'overrideText'
        ];
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $id
     */
    public function setId($id)
    {
        if (is_array($id)) {
            $id = reset($id);
        }
        $this->id = (int)$id;
    }

    /**
     * Returns the element class associated with this field type.
     *
     * @return string The Element class name
     */
    abstract protected static function elementType(): string;

    /**
     * Returns an array of variables that should be passed to the input template.
     *
     * @param ElementQueryInterface|array|null $value
     * @param ElementInterface|null            $element
     *
     * @return array
     */
    protected function inputTemplateVariables($value = null, ElementInterface $element = null): array
    {
        if ($value instanceof ElementQueryInterface) {
            $value
                ->status(null)
                ->enabledForSite(false);
        } else if (!is_array($value)) {
            /** @var Element $class */
            $class = static::elementType();
            $value = $class::find()
                ->id(false);
        }

        $selectionCriteria = $this->inputSelectionCriteria();
        $selectionCriteria['enabledForSite'] = null;
        $selectionCriteria['siteId'] = $this->targetSiteId($element);

        return [
            'jsClass' => $this->inputJsClass,
            'elementType' => static::elementType(),
            'id' => Craft::$app->getView()->formatInputId('id'),
            'fieldId' => $this->id,
            'storageKey' => 'field.'.$this->id,
            'name' => 'id',
            'elements' => $value,
            'sources' => $this->inputSources($element),
            'criteria' => $selectionCriteria,
            'sourceElementId' => !empty($element->id) ? $element->id : null,
            'limit' => 1,
            'viewMode' => $this->viewMode(),
            'selectionLabel' => Craft::t('site', $this->label)
        ];
    }

    /**
     * Returns any additional criteria parameters limiting which elements the field should be able to select.
     *
     * @return array
     */
    protected function inputSelectionCriteria(): array
    {
        return [];
    }

    /**
     * Returns the site ID that target elements should have.
     *
     * @param ElementInterface|null $element
     *
     * @return int
     */
    protected function targetSiteId(ElementInterface $element = null): int
    {
        /** @var Element|null $element */
        if (Craft::$app->getIsMultiSite()) {
            if ($this->targetSiteId) {
                return $this->targetSiteId;
            }

            if ($element !== null) {
                return $element->siteId;
            }
        }

        return Craft::$app->getSites()->currentSite->id;
    }

    /**
     * Returns an array of the source keys the field should be able to select elements from.
     *
     * @param ElementInterface|null $element
     *
     * @return array|string
     */
    protected function inputSources(ElementInterface $element = null)
    {
        if ($this->allowMultipleSources) {
            $sources = $this->sources;
        } else {
            $sources = [$this->source];
        }

        return $sources;
    }

    /**
     * Returns the field’s supported view modes.
     *
     * @return array
     */
    protected function supportedViewModes(): array
    {
        $viewModes = [
            'list' => Craft::t('app', 'List'),
        ];

        return $viewModes;
    }

    /**
     * Normalizes the available sources into select input options.
     *
     * @return array
     */
    public function getSourceOptions(): array
    {
        $options = [];
        $optionNames = [];

        foreach ($this->availableSources() as $source) {
            // Make sure it's not a heading
            if (!isset($source['heading'])) {
                $options[] = [
                    'label' => $source['label'],
                    'value' => $source['key']
                ];
                $optionNames[] = $source['label'];
            }
        }

        // Sort alphabetically
        array_multisort($optionNames, SORT_NATURAL | SORT_FLAG_CASE, $options);

        return $options;
    }

    /**
     * Returns the sources that should be available to choose from within the field's settings
     *
     * @return array
     */
    protected function availableSources(): array
    {
        return Craft::$app->getElementIndexes()->getSources(static::elementType(), 'modal');
    }

    /**
     * Returns the field’s current view mode.
     *
     * @return string
     */
    protected function viewMode(): string
    {
        $supportedViewModes = $this->supportedViewModes();
        $viewMode = $this->viewMode;

        if ($viewMode && isset($supportedViewModes[$viewMode])) {
            return $viewMode;
        }

        return 'list';
    }

    /**
     * @inheritdoc
     */
    public function getElementValidationRules(): array
    {
        return [];
    }

}
