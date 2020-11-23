<?php
namespace App\Repository;

use Exception;
class ArticlesRepo extends AbstractRepository
{
    /**
     * Returns all articles.
     * @param array|null $likedByLoggedUserSubQuery
     * @return array|string
     * @throws Exception
     */
    public function getArticles(?array $likedByLoggedUserSubQuery = null)
    {
        $parameters = ['status' => 'approved'];
        $selections = [
            'a.id',
            'a.title',
            'a.content',
            'a.cover',
            'a.slug',
            'count(DISTINCT l.liked_by)' => 'likes',
        ];
        if ($likedByLoggedUserSubQuery !== null) {
            $selections["{$likedByLoggedUserSubQuery['query']}"] = 'liked_by_current_user';
            foreach ($likedByLoggedUserSubQuery['parameters'] as $parameter => $value) {
                $parameters[$parameter] = $value;
            }
        }
        return $this->createQueryBuilder('a')
            ->select($selections)
            ->where([
                'a.status = :status'
            ])
            ->setParameters($parameters)
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
    }
}
