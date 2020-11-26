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
     * Returns all articles.
     * @param string|null $slug The article's slug. (optional - used if we need a specific article)
     * @return array
     * @throws Exception
     */
    public function getArticlesFull(?string $slug = null): array
    {
        $conditions = ['a.status = :status'];
        $parameters = [
            'status' => 'approved',
            'liked_by' => $this->userId,
            'bookmarked_by' => $this->userId,
        ];
        if (!empty($slug)) {
            $conditions[] = ['a.slug = :slug'];
            $parameters['slug'] = $slug;
        }
        $articles = $this->createQueryBuilder('a')
            ->select([
                'a.id',
                'a.title',
                'a.content',
                'a.cover',
                'a.slug',
                'a.created_at',
                'a.description',
                'count(DISTINCT l.liked_by)' => 'likes',
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
}
