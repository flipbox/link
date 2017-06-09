<?php

namespace flipbox\link\types;

use craft\base\ElementInterface;
use flipbox\link\fields\Link;

interface TypeInterface
{

    /**
     * @return string
     */
    public static function displayName(): string;

    /**
     * @return string
     */
    public function getUrl(): string;

    /**
     * @return string
     */
    public function getText(): string;

    /**
     * @param array $attributes
     * @return string
     */
    public function getHtml(array $attributes = []): string;

    /**
     * @return array
     */
    public function getSettings(): array;

    /**
     * @return array
     */
    public function getProperties(): array;

    /**
     * @return string
     */
    public function getSettingsHtml(): string;

    /**
     * @param Link $field
     * @param $value
     * @param ElementInterface|null $element
     * @return string
     */
    public function getInputHtml(Link $field, $value, ElementInterface $element = null): string;
}
