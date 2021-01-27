<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToOne;

/**
 * @property int $id
 * @property User $author        The user that created the article.
 * @property string $title       The article's title.
 * @property $slug               The article's slug.
 * @property string $cover       The article's cover image.
 * @property string $description The article's description(small text).
 * @property string $content     The article's content.
 * @property string $status      The article's status.
 * - review(default): Article that hasn't been reviewed yet.
 * - draft: Article that hasn't been submitted for review yet.
 * - approved: Article that has been submitted for review and approved.
 * - rejected: Article that has been submitted for review and was rejected.
 * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")
 * @ORM\Table(name="articles")
 */
class Article extends AbstractEntity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ManyToOne(targetEntity="App\Entity\User", inversedBy="articles")
     * @JoinColumn(nullable=false)
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
     * @ORM\Column(type="string", length=255, options={"default": "review"})
     */
    private string $status;

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
        $this->slug = $this->slugify($this->getTitle());
    }

    public function getSlug(): string
    {
        return $this->slug;
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
}
