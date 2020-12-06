<?php
namespace App\Controller;

use App\Core\Exception\MethodNotAllowedException;
use App\Core\Network\Request;
use App\Entity\Article;
use App\Component\UploadComponent;
use App\Repository\ArticleBookmarksRepo;
use App\Repository\ArticleLikesRepo;
use App\Repository\ArticlesRepo;
use App\Repository\TagsRepo;
use Exception;
use JetBrains\PhpStorm\NoReturn;

/**
 * @property ArticlesRepo $ArticlesRepo
 * @property ArticleLikesRepo $ArticleLikesRepo
 * @property ArticleBookmarksRepo $ArticleBookmarksRepo
 * @property TagsRepo $TagsRepo
 */
class ArticlesController extends AbstractController
{
    /**
     * Read a specific article
     * @param string $slug The article's slug.
     * @return void
     * @throws Exception
     */
    #[NoReturn]
    public function read(string $slug): void
    {
        $this->loadRepo('articles');
        $this->loadRepo('articleLikes');
        $this->loadRepo('articleBookmarks');
        $this->ArticlesRepo->setSubQuery('likedByLoggedUserSubQuery', $this->ArticleLikesRepo->getLikedByLoggedUserSubQuery());
        $this->ArticlesRepo->setSubQuery('bookmarkedByLoggedUserSubQuery', $this->ArticleBookmarksRepo->getBookmarkedByLoggedUserSubQuery());
        $article = $this->ArticlesRepo->getApprovedArticlesByUser($slug);
        $suggestions = $this->ArticlesRepo->getRandomArticlesByAuthor($article['author_id'], $slug);
        $article['suggestions'] = $suggestions;
        $article['content'] = $this->markdown->parse($article['content']);
        $this->render('articles/read', [
            'article' => $article,
        ]);
    }

    /**
     * Saves a new article.
     * @return void
     * @throws Exception
     */
    #[NoReturn]
    public function write(): void
    {
        if (!empty($this->auth->user)) {
            $this->loadRepo('articles');
            $this->loadRepo('tags');
            $this->loadRepo('articleTags');
            $Article = new Article($this->auth->user('id'));
            $tags = $this->TagsRepo->findAll();
            if ($this->request->is('post')) {
                // TODO: try to create a 'saveAssociated', we'll add the tags into the article entity or request data
                // then check for them when we save and use another entity in the saveAssociated method.
                var_dump($this->ArticlesRepo->save($Article)); die;
            }
            $this->render('articles/write', [
                'Article' => $Article,
                'tags' => $tags,
            ]);
        } else {
            $this->notifyError('You must be logged in in order to be able to write articles');
            $this->redirect(['path' => Request::ROOT]);
        }
    }

    /**
     * Used to preview markdown content.
     * @return void
     * @throws MethodNotAllowedException
     * @api
     */
    #[NoReturn]
    public function preview(): void
    {
        $this->methodsAllowed(['post']);
        $this->newJsonResponse($this->markdown->parse($this->request->data['content']));

    }

    /**
     * Uploads a cover image.
     * @return void
     * @throws MethodNotAllowedException
     * @api
     */
    #[NoReturn]
    public function uploadCover(): void
    {
        $this->methodsAllowed(['post']);
        $response = false;
        $errors = [];
        if (isset($_FILES['cover'])) {
            $file = $_FILES['cover'];
            $uploadHelper = new UploadComponent($file);
            if ($uploadHelper->getStatus()) {
                $response = $uploadHelper->getFilename();
            }
        }
        $this->newJsonResponse($response, $errors);
    }

    /**
     * Deletes a cover image.
     * @return void
     * @throws MethodNotAllowedException
     * @api
     */
    #[NoReturn]
    public function deleteCover(): void
    {
        $this->methodsAllowed(['post']);
        $response = false;
        $errors = [];
        $file = $this->request->data['cover'];
        if (unlink(UPLOADS . $this->request->data['cover'])) {
            $response = true;
        } else {
            $errors['message'] = "$file cannot be deleted due to an error";
        }
        $this->newJsonResponse($response, $errors);
    }
}
