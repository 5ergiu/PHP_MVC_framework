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
        if (empty($this->auth->user())) {
            $user = $this->auth->login($this->request->data['username'], $this->request->data['password']);
            if (empty($user)) {
                $user = false;
                $errors['credentials'] = 'Wrong credentials';
            } else {
                $this->notify('check', 'Successfully logged in');
                $user['redirect'] = $this->referer;
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
        if (!empty($this->auth->user())) {
            $this->auth->logout();
            $this->notify('check', 'Successfully logged out');
            $this->redirect(['path' => Request::ROOT]);
        }
    }

    /**
     * @api Checks if a user is logged in or not.
     * @return void
     */
    public function isloggedIn(): void
    {
        $response = !empty($this->auth->user());
        $this->newJsonResponse($response);
    }

    /**
     * Registers new users.
     * @return void
     * @throws Exception
     */
    public function register(): void
    {
        if (!empty($this->auth->user())) {
            $this->redirect($this->referer);
        }
        $User = new User;
        $this->loadRepo('users');
        if ($this->request->is('post')) {
            if ($this->UsersRepo->save($User, $this->request->data)) {
                $lastInsertedId = $this->UsersRepo->lastInsertedId();
                $username = $this->UsersRepo->findBy('id', $lastInsertedId)['username'];
                $user = $this->auth->login($username, null, true);
                if (!empty($user)) {
                    $this->redirect($this->referer);
                }
            }
        }
        $this->render('auth/register', [
            'User' => $User,
        ]);
    }
}
