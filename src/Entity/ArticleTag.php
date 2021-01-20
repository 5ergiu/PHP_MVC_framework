<?php

namespace App\Entity;

/**
 * @property int $articleId
 * @property int $tagId
 */
class ArticleTag extends AbstractEntity
{
    public function __construct(
        private int $articleId,
        private int $tagId
    ) {}

    /**
     * @return int
     */
    public function getArticleId(): int
    {
        return $this->articleId;
    }

    /**
     * @param int $articleId
     */
    public function setArticleId(int $articleId): void
    {
        $this->articleId = $articleId;
    }

    /**
     * @return int
     */
    public function getTagId(): int
    {
        return $this->tagId;
    }

    /**
     * @param int $tagId
     */
    public function setTagId(int $tagId): void
    {
        $this->tagId = $tagId;
    }
}
