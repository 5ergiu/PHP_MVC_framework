<?php
namespace App\Controller;

use App\Core\Network\Request;
use App\Entity\User;
use App\Repository\UsersRepo;
use Exception;
use JetBrains\PhpStorm\NoReturn;

/**
 * @property User $User
 * @property UsersRepo $UsersRepo
 */
class AuthController extends AbstractController
{
    /**
     * @api Logs users in.
     * @return void
     * @throws Exception
     */
    #[NoReturn]
    public function login(): void
    {
        $this->methodsAllowed(['post']);
        $user = null;
        $errors = [];
        if (empty($this->auth->user)) {
            $this->loadRepo('users');
            $user = $this->UsersRepo->findBy(['username' => $this->request->data['username']]);
            if (!empty($user)) {
                $authenticatedUser = $this->auth->login($user, $this->request->data['password']);
                if ($authenticatedUser) {
                    $user = $authenticatedUser;
                    $this->notifySuccess('Successfully logged in');
                    $user['redirect'] = $this->referer !== 'auth/register' ? $this->referer : Request::ROOT;
                } else {
                    $errors['credentials'] = 'Wrong credentials';
                }
            } else {
                $errors['credentials'] = 'Wrong credentials';
            }
        } else {
            $errors['user'] = 'Already logged in';
        }
        $this->newJsonResponse($user, $errors);
    }

    /**
     * @api Logs users out.
     * @return void
     * @throws Exception
     */
    #[NoReturn]
    public function logout(): void
    {
        $this->methodsAllowed(['post']);
        if (!empty($this->auth->user())) {
            $this->auth->logout();
            $this->notifySuccess('Successfully logged out');
            $this->redirect(['path' => Request::ROOT]);
        } else {
            $this->notifyError('You\'re not logged in');
            $this->redirect(['path' => Request::ROOT]);
        }
    }

    /**
     * @api Checks if a user is logged in or not.
     * @return void
     */
    #[NoReturn]
    public function isLoggedIn(): void
    {
        $response = !empty($this->auth->user);
        $this->newJsonResponse($response);
    }

    /**
     * Registers new users.
     * @return void
     * @throws Exception
     */
    #[NoReturn]
    public function register(): void
    {
        if (!empty($this->auth->user)) {
            $this->notifyError('You\'re already logged in');
            $this->redirect($this->referer);
        }
        $User = new User;
        if ($this->request->is('post')) {
            $this->loadRepo('users');
            $userId = $this->UsersRepo->save($User, $this->request->data);
            if ($userId) {
                $user = $this->UsersRepo->findById($userId);
                if ($user) {
                    $this->auth->login($user, skipVerification: true);
                    $this->redirect(['path' => Request::ROOT]);
                }
            }
            $this->notifyError('Something went wrong, please try again');
        }
        $this->render('auth/register', [
            'User' => $User,
        ]);
    }
}
