<?php
namespace App\Controller;

use App\Repository\ArticleLikesRepo;
use App\Repository\ArticlesRepo;
use Exception;
/**
 * @property ArticlesRepo $ArticlesRepo
 * @property ArticleLikesRepo $ArticleLikesRepo
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
        $likedByLoggedUserSubQuery = null;
        $userId =  $this->auth->user('id');
        $articles = $this->ArticlesRepo->getArticles(
            $this->ArticleLikesRepo->getLikedByLoggedUserSubQuery(),
            $userId
        );
        var_dump($articles); die;
        $this->render('home/index', [
//            'articles' => $articles,
        ]);
    }
}
