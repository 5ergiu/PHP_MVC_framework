<?php
namespace App\Controller;

use App\Repository\ArticleBookmarksRepo;
use App\Repository\ArticleLikesRepo;
use App\Repository\ArticlesRepo;
use App\Repository\ArticleTagsRepo;
use Exception;
/**
 * @property ArticlesRepo $ArticlesRepo
 * @property ArticleLikesRepo $ArticleLikesRepo
 * @property ArticleBookmarksRepo $ArticleBookmarksRepo
 * @property ArticleTagsRepo $ArticleTagsRepo
 */
class HomeController extends AbstractController
{
    /**
     * Index method.
     * @return void
     * @throws Exception
     */
    public function index(): void
    {
        $this->loadRepo('articles');
        $this->loadRepo('articleLikes');
        $this->loadRepo('articleBookmarks');
        $this->ArticlesRepo->setSubQuery('likedByLoggedUserSubQuery', $this->ArticleLikesRepo->getLikedByLoggedUserSubQuery());
        $this->ArticlesRepo->setSubQuery('bookmarkedByLoggedUserSubQuery', $this->ArticleBookmarksRepo->getBookmarkedByLoggedUserSubQuery());
        $userId =  $this->auth->user('id');
        $articles = $this->ArticlesRepo->getArticlesFull($userId);
        $this->render('home/index', [
            'articles' => $articles,
        ]);
    }
}
