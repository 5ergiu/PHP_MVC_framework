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
        if (!empty($userId)) {
            $likedByLoggedUserSubQuery = $this->ArticleLikesRepo->likedByLoggedUserSubQuery($userId);
        }
        $articles = $this->ArticlesRepo->getArticles($likedByLoggedUserSubQuery);
        var_dump($articles); die;
        $this->render('home/index', [
//            'articles' => $articles,
        ]);
    }
}
