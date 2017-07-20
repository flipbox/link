<?php

namespace flipbox\link\types;

use Craft;
use craft\elements\Entry as EntryElement;
use craft\base\ElementInterface;
use flipbox\link\fields\Link;
use yii\base\Exception;

class Entry extends AbstractType
{
    /**
     * @var array
     */
    public $sections = [];

    /**
     * @var bool
     */
    public $overrideText = true;

    /**
     * @var string
     */
    public $text;

    /**
     * @var int
     */
    private $id;

    /**
     * @var EntryElement
     */
    private $entry;

    /**
     * @inheritdoc
     */
    public $label = 'Select Entry';

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
     * @inheritdoc
     */
    public function getText(): string
    {
        if ($this->text !== null) {
            return $this->text;
        }
        return $this->getEntry()->title;
    }

    /**
     * @inheritdoc
     */
    public function getUrl(): string
    {
        return $this->getEntry()->getUrl();
    }

    /**
     * @return EntryElement
     * @throws Exception
     */
    public function getEntry()
    {
        if ($this->entry === null) {
            if (!$this->id || ($entry = Craft::$app->getEntries()->getEntryById($this->id)) === null) {
                throw new Exception('Entry not set');
            }

            $this->entry = $entry;
        }

        return $this->entry;
    }

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
     * @inheritdoc
     */
    public function settingsHtml(): string
    {
        return Craft::$app->getView()->renderTemplate(
            'link/_components/fieldtypes/Link/settings/entry',
            [
                'sections' => Craft::$app->getSections()->getAllSections(),
                'type' => $this
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function inputHtml(Link $field, TypeInterface $type = null, ElementInterface $element = null): string
    {
        return Craft::$app->getView()->renderTemplate(
            'link/_components/fieldtypes/Link/input/entry',
            [
                'value' => $type,
                'sourceElementId' => $element ? $element->getId() : null,
                'elements' => $this->id ? [$this->getEntry()] : [],
                'type' => $this,
                'criteria' => $this->getCriteria(),
                'field' => $field,
                'elementType' => EntryElement::class
            ]
        );
    }

    /**
     * @return array
     */
    private function getCriteria(): array
    {
        return [
            'status' => null,
            'sectionId' => $this->sections

        ];
    }
}
