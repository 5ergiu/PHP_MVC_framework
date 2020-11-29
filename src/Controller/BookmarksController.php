<?php
namespace App\Controller;

use App\Entity\ArticleBookmark;
use App\Repository\ArticleBookmarksRepo;
use Exception;
use JetBrains\PhpStorm\NoReturn;

/**
 * @property ArticleBookmarksRepo $ArticleBookmarksRepo
 */
class BookmarksController extends AbstractController
{
    /**
     * Adds a bookmark.
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
            $articleId = (int)$this->request->data['article_id'];
            $data['data']['ArticleBookmark']['article_id'] = $articleId;
            $data['data']['ArticleBookmark']['bookmarked_by'] = (int)$this->auth->user('id');
            $this->loadRepo('articleBookmarks');
            if (!$this->ArticleBookmarksRepo->exists(['article_id' => $articleId])) {
                $ArticleBookmark = new ArticleBookmark;
                if ($this->ArticleBookmarksRepo->save($ArticleBookmark, $data)) {
                    $response = true;
                    $errors = [];
                }
            }
        } else {
            $errors[] = 'You must be authenticated to bookmark articles';
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
            $articleId = (int)$this->request->data['article_id'];
            $this->loadRepo('articleBookmarks');
            $articleBookmarkId = $this->ArticleBookmarksRepo->exists(['article_id' => $articleId]);
            if ($articleBookmarkId) {
                if ($this->ArticleBookmarksRepo->delete($articleBookmarkId)) {
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
