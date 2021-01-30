<?php
namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @property int $id
 * @property string $username
 * @property string $email
 * @property string $role               User's role('User' by default).
 * @property string $password
 * @property string $image              User's profile picture.
 * @property string $summary            User's profile small description.
 * @property Collection $articles  The articles created by the user.
 * @property Collection $bookmarks The articles bookmarked by the user.
 * @property Collection $likes     The articles liked by the user.
 * @ORM\Entity(repositoryClass="App\Repository\UsersRepository")
 * @ORM\Table(name="users")
 */
class User extends AbstractEntity
{
    public const ROLE_ADMIN = 'Admin';
    public const ROLE_USER = 'User';
    public const ROLE_AUTHOR = 'Author';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true, nullable=false)
     */
    private string $username;

    /**
     * @ORM\Column(type="string", length=255, unique=true, nullable=false)
     */
    private string $email;

    /**
     * @ORM\Column(type="enumUserRoleType", options={"default": "User"})
     */
    private string $role = self::ROLE_USER;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private string $password;

    /**
     * @ORM\Column(name="joined", type="datetime", options={"default": "CURRENT_TIMESTAMP"})
     */
    private DateTime $joined;

    /**
     * @ORM\Column(type="string", length=255, options={"default": "guest.svg"})
     */
    private string $image;

    /**
     * @ORM\Column(type="string", length=255, options={"default": "I prefer to stay misterious."})
     */
    private string $summary;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Article", mappedBy="author")
     */
    private Collection $articles;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Article", mappedBy="bookmarkedBy")
     */
    private Collection $bookmarks;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Article", mappedBy="likedBy")
     */
    private Collection $likes;

    public function __construct()
    {
        $this->articles = new ArrayCollection;
        $this->bookmarks = new ArrayCollection;
        $this->likes = new ArrayCollection;
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
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getRole(): string
    {
        return $this->role;
    }

    /**
     * @param string $role
     */
    public function setRole(string $role): void
    {
        $this->role = $role;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = password_hash($password, PASSWORD_BCRYPT);
    }

    /**
     * @return DateTime
     */
    public function getJoined(): DateTime
    {
        return $this->joined;
    }

    /**
     * @return string
     */
    public function getImage(): string
    {
        return $this->image;
    }

    /**
     * @param string $image
     */
    public function setImage(string $image): void
    {
        $this->image = $image;
    }

    /**
     * @return string
     */
    public function getSummary(): string
    {
        return $this->summary;
    }

    /**
     * @param string $summary
     */
    public function setSummary(string $summary): void
    {
        $this->summary = $summary;
    }

    /**
     * @return Collection
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    /**
     * @param Article $article
     * @return $this
     */
    public function addArticle(Article $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
            $article->setAuthor($this);
        }

        return $this;
    }

    /**
     * @param Article $article
     * @return $this
     */
    public function removeArticle(Article $article): self
    {
        if ($this->articles->contains($article)) {
            $this->articles->removeElement($article);
            // set the owning side to null (unless already changed)
            if ($article->getAuthor() === $this) {
                $article->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection
     */
    public function getBookmarks(): Collection
    {
        return $this->bookmarks;
    }

    /**
     * @param Article $article
     * @return $this
     */
    public function addBookmark(Article $article): self
    {
        if (!$this->bookmarks->contains($article)) {
            $this->bookmarks[] = $article;
            $article->addBookmark($this);
        }
        return $this;
    }

    /**
     * @param Article $article
     * @return $this
     */
    public function removeBookmark(Article $article): self
    {
        if ($this->bookmarks->contains($article)) {
            $this->bookmarks->removeElement($article);
            $article->removeBookmark($this);
        }
        return $this;
    }

    /**
     * @return Collection
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    /**
     * @param Article $article
     * @return $this
     */
    public function addLike(Article $article): self
    {
        if (!$this->likes->contains($article)) {
            $this->likes[] = $article;
            $article->addLike($this);
        }
        return $this;
    }

    /**
     * @param Article $article
     * @return $this
     */
    public function removeLike(Article $article): self
    {
        if ($this->likes->contains($article)) {
            $this->likes->removeElement($article);
            $article->removeLike($this);
        }
        return $this;
    }
}
