<?php
namespace App\Entity;

/**
 * @property int $articleId
 * @property int $userId
 */
class ArticleBookmark extends AbstractEntity
{
    private int $articleId;
    private int $userId;

    public function validations(): void
    {
        // TODO: Implement validations() method.
    }

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
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }
}
