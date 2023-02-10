<?php

declare(strict_types=1);

namespace Strata\Frontend\Content\Field;

use Strata\Frontend\Content\ContentInterface;
use Strata\Frontend\Content\IterableContentInterface;
use Strata\Frontend\Content\IterableContentTrait;

/**
 * Represents one component
 *
 * @package Strata\Frontend\Content\Field
 */
class Component implements ContentInterface, IterableContentInterface
{
    use IterableContentTrait;

    protected $name;

    /**
     * Content field collection
     *
     * @var ContentFieldCollection
     */
    protected $content;

    /**
     * Constructor
     *
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->setName($name);
        $this->content = new ContentFieldCollection();
    }

    /**
     * Add new content field
     *
     * @param ContentFieldInterface $contentField
     */
    public function addContent(ContentFieldInterface $contentField)
    {
        $this->content->addItem($contentField);
        $this->collection[$contentField->getName()] = $contentField;
    }

    /**
     * Return collection of content fields
     *
     * @return ContentFieldCollection
     */
    public function getContent(): ContentFieldCollection
    {
        return $this->content;
    }

    /**
     * Get component name
     *
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set component name
     *
     * @param string $name
     * @return Component Fluent interface
     */
    public function setName(string $name): Component
    {
        $this->name = $name;
        return $this;
    }

    public function __get(string $name)
    {
        if ($this->getContent()->offsetExists($name)) {
            return $this->getContent()->offsetGet($name);
        }
        return null;
    }

    /**
     * Return string representation of content field
     *
     * @return string
     */
    public function __toString(): string
    {
        $componentContent = '';

        if (count($this->content) >= 1) {
            foreach ($this->content as $contentField) {
                $componentContent .= $contentField->__toString();
            }
        }

        return $componentContent;
    }
}
