<?php
namespace App\Repository;

use Exception;
class ArticlesRepo extends AbstractRepository
{
    /**
     * Returns all articles.
     * @param string|null $likedByLoggedUserSubQuery
     * @param int|null $userId Logged user.
     * @return array|string
     * @throws Exception
     */
    public function getArticles(string $likedByLoggedUserSubQuery, ?int $userId)
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
            ])
            ->where([
                'a.status = :status'
            ])
            ->setParameters([
                'status' => 'approved',
                'liked_by' => $userId,
            ])
            ->joins([
                [
                    'table' => 'article_likes',
                    'alias' => 'l',
                    'type' => 'LEFT',
                    'conditions' => [
                        'a.id = l.article_id',
                    ],
                ],
            ])
            ->groupBy(['a.id'])
            ->getQuery()
            ->getResults()
        ;
        foreach ($articles as &$article) {
            $article['liked_by_logged_user'] =
                $article['liked_by_logged_user'] === '1';
        }
        return $articles;
    }
}
