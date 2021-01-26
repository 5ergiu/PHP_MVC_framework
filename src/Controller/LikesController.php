<?php
namespace App\Controller;

use App\Entity\ArticleLike;
use App\Repository\ArticleLikesRepo;
use Exception;
use JetBrains\PhpStorm\NoReturn;

/**
 * @property ArticleLikesRepo $ArticleLikesRepo
 */
class LikesController extends AbstractController
{
    /**
     * Adds a like.
     * @return void
     * @throws Exception
     */
    #[NoReturn]
    public function add()
    {
        $this->methodsAllowed(['post']);
        $response = false;
        $errors[] = 'Something went wrong';
        if (!empty($this->auth->user)) {
            $articleId = (int)$this->request->query->get('ArticleLike')['article_id'];
            $this->loadRepo('articleLikes');
            if (!$this->ArticleLikesRepo->exists(['article_id' => $articleId])) {
                $ArticleLike = new ArticleLike($this->auth->user('id'));
                if ($this->ArticleLikesRepo->save($ArticleLike)) {
                    $response = true;
                    $errors = [];
                }
            }
        } else {
            $errors[] = 'You must be authenticated to like articles';
        }
        $this->newJsonResponse($response, $errors);
    }

    /**
     * Removes a bookmark.
     * @return void
     * @throws Exception
     */
    #[NoReturn]
    public function remove()
    {
        $this->methodsAllowed(['post']);
        $response = false;
        $errors[] = 'Something went wrong';
        if (!empty($this->auth->user)) {
            $articleId = (int)$this->request->query->get('ArticleLike')['article_id'];
            $this->loadRepo('articleLikes');
            $articleLikeId = $this->ArticleLikesRepo->exists(['article_id' => $articleId]);
            if ($articleLikeId) {
                if ($this->ArticleLikesRepo->delete($articleLikeId)) {
                    $response = true;
                    $errors = [];
                }
            }
        } else {
            $errors[] = 'What are you trying to do? 🤨';
        }
        $this->newJsonResponse($response, $errors);
    }
}
