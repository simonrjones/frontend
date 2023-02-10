<?php

declare(strict_types=1);

namespace Strata\Frontend\Content;

/**
 * Class Page
 * @package Strata\Frontend\Content
 */
class Page extends BaseContent
{

    /**
     * @var
     */
    protected $excerpt;

    /**
     * Author user
     * @var User
     */
    protected $author;

    /**
     * Head head
     * @var $head
     */
    protected Head $head;

    /**
     * Template template
     * @var $template
     */
    protected $template;

    protected Url $urlPattern;

    /**
     * Page constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->head = new Head();
    }

    /**
     * @return Head
     */
    public function getHead(): Head
    {
        return $this->head;
    }

    /**
     * Set URL pattern
     *
     * @param $urlPattern
     */
    public function setUrlPattern(Url $urlPattern)
    {
        $this->urlPattern = $urlPattern;
    }

    /**
     * Get URL pattern
     *
     * @return Url
     */
    public function getUrlPattern(): Url
    {
        return $this->urlPattern;
    }

    /**
     * Return the full URL to the current page
     *
     * @return string
     */
    public function getUrl(): string
    {
        return $this->urlPattern->getUrl($this);
    }

    /**
     * Return excerpt, if not set auto-generates this based on trimmed page content
     *
     * @param int $limit Character length to trim string to
     * @return mixed
     */
    public function getExcerpt(int $limit = 200): string
    {
        if (empty($this->excerpt)) {
            return $this->trimContent($this->__toString(), $limit);
        }

        return $this->excerpt;
    }


    /**
     * Return a shorter version of content, cut to word boundaries and stripped of any HTML
     *
     * @param string $content String
     * @param int $limit Character length to trim string to
     * @return string Concatenated string
     */
    public function trimContent(string $content, int $limit = 200): string
    {
        $content = strip_tags($content);
        $content = trim($content);

        if (preg_match('/^.{1,' . $limit . '}\b/s', $content, $m)) {
            $content = trim($m[0]);
        } else {
            // If preg doesn't work just cut string
            $content = substr($content, 0, $limit);
        }

        return trim($content);
    }

    /**
     * @param mixed $excerpt
     * @return Page
     */
    public function setExcerpt($excerpt): Page
    {
        $this->excerpt = $excerpt;
        return $this;
    }

    /**
     * @return User
     */
    public function getAuthor()
    {
        if (!empty($this->author)) {
            return $this->author;
        }

        if ($this->getContent()->offsetExists('author')) {
            return $this->getContent()->offsetGet('author');
        }

        return $this->author;
    }

    /**
     * Set author user
     *
     * @param User $author
     * @return Page
     */
    public function setAuthor($author): Page
    {
        $this->author = $author;
        return $this;
    }

    /**
     * Set page template
     *
     * @param $template
     * @return Page
     */
    public function setTemplate($template): Page
    {
        $this->template = $template;
        return $this;
    }

    public function getTemplate(): ?string
    {
        return $this->template;
    }
}
