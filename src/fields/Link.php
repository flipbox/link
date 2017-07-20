<?php

namespace flipbox\link\fields;

use Craft;
use craft\base\ElementInterface;
use craft\base\Field;
use craft\helpers\ArrayHelper;
use craft\helpers\Db;
use craft\helpers\Json;
use flipbox\link\Link as LinkPlugin;
use flipbox\link\types\TypeInterface;
use yii\db\Schema;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
class Link extends Field
{

    /**
     * @var TypeInterface[]
     */
    protected $types = [];

    /**
     * @var array
     */
    protected $typeConfigs = [];

    /**
     * @return TypeInterface[]
     */
    public function getTypes()
    {
        return $this->types;
    }

    /**
     * @param array $types
     */
    public function setTypes(array $types)
    {
        foreach ($types as $type) {
            if ($type = $this->resolveType($type)) {
                $this->types[get_class($type)] = $type;
            }
        }
    }

    /**
     * @param string $type
     * @return TypeInterface
     */
    public function getType(string $type)
    {
        return ArrayHelper::getValue($this->types, $type);
    }

    /**
     * @param $type
     * @return null|TypeInterface
     */
    protected function resolveType($type)
    {
        if (is_array($type)) {
            if (!isset($type['class']) || empty($type['class'])) {
                return null;
            }

            $type = \Yii::createObject(
                $type
            );
        }

        if (!$type instanceof TypeInterface) {
            return null;
        }

        return $type;
    }


    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
        return Craft::t('link', 'Link');
    }

    /**
     * @inheritdoc
     */
    public function getContentColumnType(): string
    {
        return Schema::TYPE_TEXT;
    }

    /**
     * @inheritdoc
     */
    public function getSettingsHtml()
    {
        return Craft::$app->getView()->renderTemplate(
            'link/_components/fieldtypes/Link/settings',
            [
                'field' => $this,
                'types' => LinkPlugin::getInstance()->getType()->findAll()
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function getInputHtml($value, ElementInterface $element = null): string
    {
        return Craft::$app->getView()->renderTemplate(
            'link/_components/fieldtypes/Link/input',
            [
                'field' => $this,
                'value' => $value,
                'element' => $element
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function serializeValue($value, ElementInterface $element = null)
    {
        if ($value === null) {
            return $value;
        }

        if($value instanceof TypeInterface) {
            $value = array_merge(
                [
                    'class' => get_class($value),
                ],
                $value->getProperties()
            );
        }

        return Db::prepareValueForDb($value);
    }

    /**
     * @inheritdoc
     */
    public function getSettings(): array
    {
        $settings = parent::getSettings();

        // Merge the type settings
        foreach ($this->getTypes() as $key => $type) {
            $settings['types'][$key] = $type->getSettings();
            $settings['types'][$key]['class'] = $key;
        }

        return $settings;
    }

    /**
     * @param mixed $value
     * @param ElementInterface|null $element
     * @return array|mixed|null|object
     */
    public function normalizeValue($value, ElementInterface $element = null)
    {
        if (is_string($value) && !empty($value)) {
            $value = Json::decodeIfJson($value);
        }

        if ($value instanceof TypeInterface) {
            return $value;
        }

        if (!is_array($value)) {
            return null;
        }

        if (!$class = ArrayHelper::remove($value, 'class')) {
            return null;
        }

        if ($types = ArrayHelper::remove($value, 'types')) {
            $value = array_merge(
                ArrayHelper::remove($types, $class, []),
                $value
            );
        }

        $type = $this->getType($class);

        if (!$type instanceof TypeInterface) {
            return null;
        }

        LinkPlugin::getInstance()->getType()->populate(
            $type,
            $value
        );

        return $type;
    }
}
