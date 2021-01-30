<?php
namespace App\Controller;

use App\Repository\ArticleBookmarksRepo;
use App\Repository\ArticleLikesRepo;
use App\Repository\ArticlesRepo;
use App\Repository\ArticleTagsRepo;
use App\Repository\UsersRepository;
use App\Repository\UsersRepo;
use Exception;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
* // * @property ArticlesRepo $ArticlesRepo
* // * @property ArticleLikesRepo $ArticleLikesRepo
* // * @property ArticleBookmarksRepo $ArticleBookmarksRepo
* // * @property ArticleTagsRepo $ArticleTagsRepo
 * @property UsersRepository $UserRepo
 */
class HomeController extends AbstractController
{
    /**
     * Index method.
     * @return Response
     * @throws Exception
     */
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        $this->loadRepo('user');
//        var_dump('intra'); die;
        var_dump($this->UserRepo->find(9)->getLikes()->toArray()); die;
//        $this->loadRepo('articles');
//        $this->loadRepo('articleLikes');
//        $this->loadRepo('articleBookmarks');
//        $this->ArticlesRepo->setSubQuery('likedByLoggedUserSubQuery', $this->ArticleLikesRepo->getLikedByLoggedUserSubQuery());
//        $this->ArticlesRepo->setSubQuery('bookmarkedByLoggedUserSubQuery', $this->ArticleBookmarksRepo->getBookmarkedByLoggedUserSubQuery());
//        $articles = $this->ArticlesRepo->getApprovedArticlesByUser();
//        return $this->render('home/index', [
//            'articles' => $articles,
//        ]);
    }
}
