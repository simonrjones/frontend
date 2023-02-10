<?php

declare(strict_types=1);

namespace Strata\Frontend\Content\Field;

use Strata\Frontend\Exception\ContentFieldException;

/**
 * Core content field functionality
 *
 * @package Strata\Frontend\Content\Field
 */
abstract class ContentField implements ContentFieldInterface
{
    /**
     * Define this constant in child classes
     */
    const TYPE = 'undefined';

    /**
     * Content field name
     * @var string
     */
    protected $name;

    /**
     * Return content field type
     *
     * TYPE constant must be defined in child classes
     *
     * @return string
     */
    public function getType(): string
    {
        return $this::TYPE;
    }

    /**
     * Does the content field contain HTML?
     *
     * @return bool
     */
    public function hasHtml(): bool
    {
        return false;
    }

    /**
     * Get content field name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set content field name
     *
     * @param string $name Content field name, can only use a-z, 0-9, underscore _ and dash -
     * @return ContentInterface
     * @throws ContentFieldException
     */
    public function setName(string $name): ContentFieldInterface
    {
        if (!preg_match('/^[a-z0-9_-]+$/i', $name)) {
            throw new ContentFieldException(sprintf('Invalid content field name: %s', $name));
        }
        $this->name = $name;
        return $this;
    }
}
