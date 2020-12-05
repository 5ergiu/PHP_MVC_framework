<?php
namespace App\Entity;

/**
 * @property int $articleId
 * @property int $bookmarkedBy
 */
class ArticleBookmark extends AbstractEntity
{
    private int $articleId;
    private int $bookmarkedBy;

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
    public function getBookmarkedBy(): int
    {
        return $this->bookmarkedBy;
    }

    /**
     * @param int $userId
     */
    public function setBookmarkedBy(int $userId): void
    {
        $this->bookmarkedBy = $userId;
    }
}
