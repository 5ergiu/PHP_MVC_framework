<?php
namespace App\Controller;

use App\Core\Network\Request;
use App\Entity\User;
use App\Repository\UsersRepo;
use Exception;
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
                if (!empty($authenticatedUser)) {
                    $user = $authenticatedUser;
                    $this->notify('check', 'Successfully logged in');
                    $user['redirect'] = $this->referer;
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
    public function logout(): void
    {
        $this->methodsAllowed(['post']);
        if (!empty($this->auth->user())) {
            $this->auth->logout();
            $this->notify('check', 'Successfully logged out');
            $this->redirect(['path' => Request::ROOT]);
        } else {
            $this->notify('error', 'You\'re not logged in');
            $this->redirect(['path' => Request::ROOT]);
        }
    }

    /**
     * @api Checks if a user is logged in or not.
     * @return void
     */
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
    public function register(): void
    {
        if (!empty($this->auth->user)) {
            $this->notify('error', 'You\'re already logged in');
            $this->redirect($this->referer);
        }
        $User = new User;
        if ($this->request->is('post')) {
            $this->loadRepo('users');
            $userId = $this->UsersRepo->save($User, $this->request->data);
            if ($userId) {
                $user = $this->UsersRepo->findById($userId);
                if (!empty($user)) {
                    $this->auth->login($user, null, true);
                    $this->redirect(['path' => Request::ROOT]);
                }
            }
            $this->notify('error', 'Something went wrong, please try again');
        }
        $this->render('auth/register', [
            'User' => $User,
        ]);
    }
}
