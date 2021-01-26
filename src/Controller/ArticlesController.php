<?php
namespace App\Controller;

use App\Core\Exception\MethodNotAllowedException;
use App\Entity\Article;
use App\Component\UploadComponent;
use App\Entity\ArticleTag;
use App\Repository\ArticleBookmarksRepo;
use App\Repository\ArticleLikesRepo;
use App\Repository\ArticlesRepo;
use App\Repository\ArticleTagsRepo;
use App\Repository\TagsRepo;
use Exception;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Component\HttpFoundation\Response;

/**
 * @property ArticlesRepo $ArticlesRepo
 * @property ArticleLikesRepo $ArticleLikesRepo
 * @property ArticleBookmarksRepo $ArticleBookmarksRepo
 * @property ArticleTagsRepo $ArticleTagsRepo
 * @property TagsRepo $TagsRepo
 */
class ArticlesController extends AbstractController
{
    /**
     * Read a specific article
     * @param string $slug The article's slug.
     * @return Response
     * @throws Exception
     */
    #[NoReturn]
    public function read(string $slug): Response
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
        return $this->render('articles/read', [
            'article' => $article,
        ]);
    }

    /**
     * Saves a new article.
     * @return Response
     * @throws Exception
     */
    #[NoReturn]
    public function write(): Response
    {
        if (!empty($this->auth->user)) {
            $this->loadRepo('articles');
            $this->loadRepo('tags');
            $this->loadRepo('articleTags');
            $Article = new Article($this->auth->user('id'));
            $tags = $this->TagsRepo->findAll();
            if ($this->request->is('post')) {
                $articleId = $this->ArticlesRepo->save($Article);
                $articleTags = $this->request->query->get('tags');
                if ($articleId) {
                    $articleTagsErrors = true;
                    if (!empty($articleTags)) {
                        foreach ($articleTags as $articleTagId) {
                            $ArticleTag = new ArticleTag($articleId, $articleTagId);
                            if ($this->ArticleTagsRepo->save($ArticleTag)) {
                                $articleTagsErrors = false;
                            } else {
                                $articleTagsErrors = true;
                            }
                        }
                        if ($articleTagsErrors) {
                            $this->ArticlesRepo->delete($articleId);
                            $this->notifyError('Article tags have\'nt been saved, please try again.');
                        } else {
                            $this->notifySuccess('Article successfully saved and submitted to review.');
                        }
                    }
                } else {
                    $this->notifyError('Article hasn\'nt been saved, please try again.');
                }
            }
            return $this->render('articles/write', [
                'Article' => $Article,
                'tags' => $tags,
            ]);
        } else {
            $this->notifyError('You must be logged in in order to be able to write articles');
            $this->redirect('/');
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
        $this->newJsonResponse($this->markdown->parse($this->request->query->get('content')));

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
        $file = $this->request->query->get('cover');
        if (unlink(UPLOADS . $this->request->query->get('cover'))) {
            $response = true;
        } else {
            $errors['message'] = "$file cannot be deleted due to an error";
        }
        $this->newJsonResponse($response, $errors);
    }

    /**
     * Deletes all temp files stored.
     * @return void
     */
    private function __deleteAllTempFiles(): void
    {
        $filesToDelete = scandir(UPLOADS);
        var_dump($filesToDelete); die;
    }
}
