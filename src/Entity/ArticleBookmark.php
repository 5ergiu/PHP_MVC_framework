<?php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @property int $id
 * @property int $articleId
 * @property int $bookmarkedBy
 * @property Collection $articleBookmarks
 * @ORM\Entity(repositoryClass="App\Repository\ArticleBookmarksRepository")
 * @ORM\Table(name="article_bookmark")
 */
class ArticleBookmark extends AbstractEntity
{

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Article", inversedBy="articleBookmarks")
     * @ORM\JoinColumn(nullable=false)
     */
    private int $article;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="articleBookmarks")
     * @ORM\JoinColumn(nullable=false)
     */
    private int $bookmarkedBy;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ArticleComment", mappedBy="parent")
     */
    private ArrayCollection $articleBookmarks;

    public function __construct()
    {
        $this->articleBookmarks = new ArrayCollection;
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
    public function getBookmarkedBy(): int
    {
        return $this->bookmarkedBy;
    }

    /**
     * @return Collection
     */
    public function getArticleBookmarks(): Collection
    {
        return $this->articleBookmarks;
    }

    public function addArticleBookmarks(self $articleBookmark): self
    {
        if (!$this->articleBookmarks->contains($articleBookmark)) {
            $this->articleBookmarks[] = $articleBookmark;
            $articleBookmark->setParent($this);
        }

        return $this;
    }

    public function removeArticleBookmark(self $articleBookmark): self
    {
        if ($this->articleBookmarks->contains($articleBookmark)) {
            $this->articleBookmarks->removeElement($articleBookmark);
            // set the owning side to null (unless already changed)
            if ($articleBookmark->getParent() === $this) {
                $articleBookmark->setParent(null);
            }
        }

        return $this;
    }
}
