<?php
namespace App\Entity;

/**
 * @property int $articleId
 * @propery int $likedBy
 */
class ArticleLike extends AbstractEntity
{
    private int $articleId;
    private int $likedBy;

    /**
     * @inheritDoc
     * @return void
     */
    protected function validations(): void
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
    public function getLikedBy(): int
    {
        return $this->likedBy;
    }

    /**
     * @param int $likedBy
     */
    public function setLikedBy(int $likedBy): void
    {
        $this->likedBy = $likedBy;
    }
}
