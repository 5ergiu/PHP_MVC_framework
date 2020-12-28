<?php
namespace App\Entity;

/**
 * @property int $articleId
 * @property int $likedBy
 */
class ArticleLike extends AbstractEntity
{
    private int $articleId;

    public function __construct(
        private int $likedBy
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
    public function getLikedBy(): int
    {
        return $this->likedBy;
    }
}
