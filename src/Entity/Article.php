<?php
namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use JetBrains\PhpStorm\Pure;

/**
 * @property int $id
 * @property User $author        The user that created the article.
 * @property string $title       The article's title.
 * @property $slug               The article's slug.
 * @property string $cover       The article's cover image.
 * @property string $description The article's description(small text).
 * @property string $content     The article's content.
 * @property string $status      The article's status.
 * @property Collection $bookmarkedBy Users that bookmarked the article.
 * @property Collection $likedBy      Users that liked the article.
 * @property Collection $tags         The tags for the article.
 * - review(default): Article that hasn't been reviewed yet.
 * - draft: Article that hasn't been submitted for review yet.
 * - approved: Article that has been submitted for review and approved.
 * - rejected: Article that has been submitted for review and was rejected.
 * @ORM\Entity(repositoryClass="App\Repository\ArticlesRepository")
 * @ORM\Table(name="articles")
 */
class Article extends AbstractEntity
{
    public const STATUSES = [
      'review' => 'REVIEW',
      'draft' => 'DRAFT',
      'approved' => 'APPROVED',
      'rejected' => 'REJECTED',
    ];

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ManyToOne(targetEntity="App\Entity\User", inversedBy="articles")
     * @JoinColumn(name="author_id", referencedColumnName="id", nullable=false)
     */
    private User $author;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private string $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private string $slug;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private string $cover;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private string $content;

    /**
     * @ORM\Column(type="enumArticleStatusType", options={"default": "review"})
     */
    private string $status = self::STATUSES['review'];

    /**
     * @ORM\Column(name="created_at", type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     */
    private DateTime $createdAt;


    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="bookmarks")
     * @ORM\JoinTable(name="article_bookmark",
     *   joinColumns={
     *     @ORM\JoinColumn(name="article_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="bookmarked_by", referencedColumnName="id")
     *   }
     * )
     */
    private Collection $bookmarkedBy;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="likes")
     * @ORM\JoinTable(name="article_like",
     *   joinColumns={
     *     @ORM\JoinColumn(name="article_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="liked_by", referencedColumnName="id")
     *   }
     * )
     */
    private Collection $likedBy;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Tag", inversedBy="articles")
     */
    private Collection $tags;

    #[Pure]
    public function __construct()
    {
        $this->bookmarkedBy = new ArrayCollection;
        $this->likedBy = new ArrayCollection;
        $this->tags = new ArrayCollection;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return User
     */
    public function getAuthor(): User
    {
        return $this->author;
    }

    /**
     * @param User $author
     * @return $this
     */
    public function setAuthor(User $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
        $this->setSlug($title);
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @param string $title
     */
    public function setSlug(string $title): void
    {
        $this->slug = $this->slugify($title);
    }

    /**
     * @return string
     */
    public function getCover(): string
    {
        return $this->cover;
    }

    /**
     * @param string $cover
     */
    public function setCover(string $cover): void
    {
        $this->cover = $cover;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return Collection
     */
    public function getBookmarks(): Collection
    {
        return $this->bookmarkedBy;
    }

    /**
     * @param User $user
     * @return self
     */
    public function addBookmark(User $user): self
    {
        if (!$this->bookmarkedBy->contains($user)) {
            $this->bookmarkedBy[] = $user;
        }
        return $this;
    }

    /**
     * @param User $user
     * @return self
     */
    public function removeBookmark(User $user): self
    {
        if ($this->bookmarkedBy->contains($user)) {
            $this->bookmarkedBy->remove($user);
        }
        return $this;
    }

    /**
     * @return Collection
     */
    public function getLikes(): Collection
    {
        return $this->likedBy;
    }

    /**
     * @param User $user
     * @return self
     */
    public function addLike(User $user): self
    {
        if (!$this->likedBy->contains($user)) {
            $this->likedBy[] = $user;
        }
        return $this;
    }

    /**
     * @param User $user
     * @return self
     */
    public function removeLike(User $user): self
    {
        if ($this->likedBy->contains($user)) {
            $this->likedBy->remove($user);
        }
        return $this;
    }

    /**
     * @return Collection
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    /**
     * @param Tag $tag
     * @return self
     */
    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }
        return $this;
    }

    /**
     * @param Tag $tag
     * @return self
     */
    public function removeTag(Tag $tag): self
    {
        if ($this->tags->contains($tag)) {
            $this->tags->remove($tag);
        }
        return $this;
    }
}
