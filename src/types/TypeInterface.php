<?php

/**
 * @copyright  Copyright (c) Flipbox Digital Limited
 * @license    https://flipboxfactory.com/software/link/license
 * @link       https://www.flipboxfactory.com/software/link/
 */

namespace flipbox\link\types;

use craft\base\ElementInterface;
use flipbox\link\fields\Link;

/**
 * @author Flipbox Factory <hello@flipboxfactory.com>
 * @since 1.0.0
 */
interface TypeInterface
{

    /**
     * @return string
     */
    public static function displayName(): string;

    /**
     * @return string
     */
    public function getIdentifier(): string;

    /**
     * @param string $identifier
     * @return static
     */
    public function setIdentifier(string $identifier);

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
    public function settingsHtml(): string;

    /**
     * @param Link $field
     * @param TypeInterface $type
     * @param ElementInterface|null $element
     * @return string
     */
    public function inputHtml(Link $field, TypeInterface $type = null, ElementInterface $element = null): string;
}
