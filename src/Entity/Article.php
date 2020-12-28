<?php
namespace App\Entity;

/**
 * @property int $authorId       The id of the user that created the article.
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
 */
class Article extends AbstractEntity
{
    private string $title;
    private string $slug;
    private string $cover;
    private string $content;
    private string $status = 'review';

    public function __construct(
       private int $authorId
    ) {}

    /**
     * @return int
     */
    public function getAuthorId(): int
    {
        return $this->authorId;
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
