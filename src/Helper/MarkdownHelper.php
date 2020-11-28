<?php
namespace App\Helper;


/**
// * @property Parsedown $parsedown
 */
class MarkdownHelper
{
//    private Parsedown $parsedown;

    public function __construct()
    {
//        $this->parsedown = new Parsedown;
//        $this->parsedown->setSafeMode(true);
//        $this->parsedown->setMarkupEscaped(true);
    }

    /**
     * Transforms a given text to markdown.
     * @param string $text The text that will be transformed to markdown.
     * @return string
     */
    public function transform(string $text): string
    {
//        return $this->parsedown->text($text);
    }
}
