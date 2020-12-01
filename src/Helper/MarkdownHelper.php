<?php
namespace App\Helper;

use cebe\markdown\GithubMarkdown;

/**
 * @property GithubMarkdown $parser
 */
class MarkdownHelper
{
    private GithubMarkdown $parser;

    public function __construct()
    {
        $this->parser = new GithubMarkdown;
        $this->parser->html5 = true;
        $this->parser->enableNewlines = true;
    }

    /**
     * TODO: implement something here(a private method perhaps) that will check for XSS or any other attacks before
     * rendering the html. TODO: same goes for when you save the content to the database, HTML purifier or something
     * Transforms a given text to markdown.
     * @param string $text The text that will be transformed to markdown.
     * @return string
     */
    public function parse(string $text): string
    {
        return $this->parser->parse($text);
    }
}
