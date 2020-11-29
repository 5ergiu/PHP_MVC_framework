<?php
namespace App\Repository;

use Exception;
/**
 * @property string $likedByLoggedUserSubQuery      Sub query to find the article liked by the logged user.
 * @property string $bookmarkedByLoggedUserSubQuery Sub query to find the article bookmarked by the logged user.
 */
class ArticlesRepo extends AbstractRepository
{
    /**
     * Returns all approved articles based on user.
     * @param string|null $slug The article's slug. (optional - used if we need to have a specific article)
     * @param int|null $limit   The query limit (optional)
     * @return array
     * @throws Exception
     */
    public function getApprovedArticlesByUser(?string $slug = null, ?int $limit = null): array
    {
        $conditions['AND'] = ["a.status = 'approved'"];
        $parameters = [
            'liked_by' => $this->userId,
            'bookmarked_by' => $this->userId,
        ];
        if (!empty($slug)) {
            $conditions['AND'][] = 'a.slug = :slug';
            $parameters['slug'] = $slug;
        }
        $articles = $this->createQueryBuilder('a')
            ->select([
                'a.id',
                'a.author_id',
                'a.title',
                'a.content',
                'a.cover',
                'a.slug',
                'a.created_at',
                'a.description',
                'count(DISTINCT l.liked_by)' => 'likes',
                'count(DISTINCT b.bookmarked_by)' => 'bookmarks',
                $this->likedByLoggedUserSubQuery => 'liked_by_logged_user',
                $this->bookmarkedByLoggedUserSubQuery => 'bookmarked_by_logged_user',
                'GROUP_CONCAT(DISTINCT t.name)' => 'tags',
                'u.username',
                'u.summary' => 'user_summary',
                'u.image' => 'avatar',
                'u.joined' => 'user_joined',
            ])
            ->where($conditions)
            ->setParameters($parameters)
            ->joins([
                [
                    'table' => 'article_likes',
                    'alias' => 'l',
                    'type' => 'LEFT',
                    'conditions' => [
                        'l.article_id = a.id',
                    ],
                ],
                [
                    'table' => 'article_bookmarks',
                    'alias' => 'b',
                    'type' => 'LEFT',
                    'conditions' => [
                        'b.article_id = a.id',
                    ],
                ],
                [
                    'table' => 'article_tags',
                    'alias' => 'atg',
                    'type' => 'LEFT',
                    'conditions' => [
                        'atg.article_id = a.id',
                    ],
                ],
                [
                    'table' => 'users',
                    'alias' => 'u',
                    'type' => 'left',
                    'conditions' => [
                        'a.author_id = u.id',
                    ],
                ],
                [
                    'table' => 'tags',
                    'alias' => 't',
                    'type' => 'left',
                    'conditions' => [
                        't.id = atg.tag_id',
                    ],
                ]
            ])
            ->groupBy(['a.id'])
            ->getQuery()
            ->getResults()
        ;
        foreach ($articles as &$article) {
            $article['liked_by_logged_user'] = $article['liked_by_logged_user'] === '1';
            $article['bookmarked_by_logged_user'] = $article['bookmarked_by_logged_user'] === '1';
            if (!empty($article['tags'])) {
                $article['tags'] = explode(',', $article['tags']);
            }
        }
        // Slugs are unique, so we'll return the first article found.
        if (!empty($slug)) {
            return $articles[0];
        }
        return $articles;
    }

    /**
     * Returns a limited number of random articles based on the author.
     * @param int $authorId     The article's author id.
     * @param string $slug      Article that will be ignored.
     * @return array
     * @throws Exception
     */
    public function getRandomArticlesByAuthor(int $authorId, string $slug): array
    {
        $conditions['AND'] = [
            "a.status = 'approved'",
            'a.author_id = :author_id',
        ];
        $parameters = [
            'author_id' => $authorId,
        ];
        if (!empty($slug)) {
            $conditions['AND'][] = 'a.slug != :slug';
            $parameters['slug'] = $slug;
        }
        $article = $this->createQueryBuilder('a')
            ->select([
                'a.id',
                'a.title',
                'a.slug',
                'GROUP_CONCAT(DISTINCT t.name)' => 'tags',
            ])
            ->setParameters($parameters)
            ->where($conditions)
            ->joins([
                [
                    'table' => 'article_tags',
                    'alias' => 'atg',
                    'type' => 'LEFT',
                    'conditions' => [
                        'atg.article_id = a.id',
                    ],
                ],
                [
                    'table' => 'tags',
                    'alias' => 't',
                    'type' => 'left',
                    'conditions' => [
                        't.id = atg.tag_id',
                    ],
                ]
            ])
            ->groupBy(['a.id'])
            ->orderBy(['RAND()'])
            ->setMaxResults(3)
            ->getQuery()
            ->getResults()
        ;
        foreach ($article as &$values) {
            if (!empty($values['tags'])) {
                $values['tags'] = explode(',', $values['tags']);
            }
        }
        return $article;
    }
}
