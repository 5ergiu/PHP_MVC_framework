<?php
namespace App\Repository;

use Exception;
class ArticlesRepo extends AbstractRepository
{
    /**
     * Returns all articles.
     * @param string $likedByLoggedUserSubQuery
     * @param string $bookmarkedByLoggedUserSubQuery
     * @param int|null $userId Logged user.
     * @return array|string
     * @throws Exception
     */
    public function getArticlesFull(
        string $likedByLoggedUserSubQuery,
        string $bookmarkedByLoggedUserSubQuery,
        ?int $userId
    )
    {
        $articles = $this->createQueryBuilder('a')
            ->select([
                'a.id',
                'a.title',
                'a.content',
                'a.cover',
                'a.slug',
                'count(DISTINCT l.liked_by)' => 'likes',
                $likedByLoggedUserSubQuery => 'liked_by_logged_user',
                $bookmarkedByLoggedUserSubQuery => 'bookmarked_by_logged_user',
                'GROUP_CONCAT(DISTINCT t.name)' => 'tags',
                'u.username',
                'u.summary' => 'user_summary',
                'u.image' => 'avatar',
                'u.joined' => 'user_joined',
            ])
            ->where([
                'a.status = :status'
            ])
            ->setParameters([
                'status' => 'approved',
                'liked_by' => $userId,
                'bookmarked_by' => $userId,
            ])
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
        return $articles;
    }
}
