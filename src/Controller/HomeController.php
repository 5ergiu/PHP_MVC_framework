<?php
namespace App\Controller;

use App\Repository\ArticleBookmarksRepo;
use App\Repository\ArticleLikesRepo;
use App\Repository\ArticlesRepo;
use App\Repository\ArticleTagsRepo;
use App\Repository\UsersRepo;
use Exception;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Component\HttpFoundation\Response;

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
     * @return Response
     * @throws Exception
     */
    public function index(): Response
    {
        $this->loadRepo('articles');
        $this->loadRepo('articleLikes');
        $this->loadRepo('articleBookmarks');
        $this->ArticlesRepo->setSubQuery('likedByLoggedUserSubQuery', $this->ArticleLikesRepo->getLikedByLoggedUserSubQuery());
        $this->ArticlesRepo->setSubQuery('bookmarkedByLoggedUserSubQuery', $this->ArticleBookmarksRepo->getBookmarkedByLoggedUserSubQuery());
        $articles = $this->ArticlesRepo->getApprovedArticlesByUser();
        return $this->render('home/index', [
            'articles' => $articles,
        ]);
    }
}
