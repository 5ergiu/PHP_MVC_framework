<?php
namespace App\Controller;

use App\Entity\ArticleBookmark;
use App\Repository\ArticleBookmarksRepo;
use Exception;
/**
 * @property ArticleBookmarksRepo $ArticleBookmarksRepo
 */
class BookmarksController extends AbstractController
{
    /**
     * Adds or removes a bookmark.
     * @return void
     * @throws Exception
     */
    public function bookmark()
    {
        $this->methodsAllowed(['post']);
        $result = false;
        $this->loadRepo('articleBookmarks');
        if ($this->ArticleBookmarksRepo->exists(['id' => $this->request->data['article']])) {
            $ArticleBookmark = new ArticleBookmark;
            $result = $this->ArticleBookmarksRepo->save($ArticleBookmark, $this->request->data);
            if ($result) {
                var_dump('OTII WAII CA MERJII WAAAI' . $result);
            }
        } else {
            $result = $this->ArticleBookmarksRepo->delete();
        }
    }
}
