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
     * Logs users in.
     * @return void
     * @throws Exception
     */
    public function login(): void
    {
        $user = null;
        $errors = [];
        if ($this->request->is('post')) {
            if (empty($this->auth->user())) {
                $user = $this->auth->login($this->request->data['username'], $this->request->data['password']);
                if (empty($user)) {
                    $errors['credentials'] = 'Wrong credentials';
                }
            } else {
                $errors['user'] = 'Already logged in';
            }
        } else {
            $errors['method'] = 'Method not allowed';
        }
        $this->newJsonResponse($user, $errors);
    }

    /**
     * Logs users out.
     * @return void
     * @throws Exception
     */
    public function logout(): void
    {
        if (!empty($this->auth->user())) {
            $this->auth->logout();
            $this->redirect(['path' => Request::ROOT]);
        } else {
            var_dump('error, still logged in'); die;
        }
    }

    /**
     * Registers new users.
     * @return void
     * @throws Exception
     */
    public function register(): void
    {
        if (!empty($this->auth->user())) {
            $this->redirect(['path' => Request::ROOT]);
        }
        $User = new User;
        $this->loadRepo('users');
        if ($this->request->is('post')) {
            if ($this->UsersRepo->save($User, $this->request->data)) {
                $lastInsertedId = $this->UsersRepo->lastInsertedId();
                $username = $this->UsersRepo->findBy('id', $lastInsertedId)['username'];
                $user = $this->auth->login($username, null, true);
                if (!empty($user)) {
                    $this->redirect(['path' => Request::ROOT]);
                }
            }
        }
        $this->render('auth/register', [
            'User' => $User,
        ]);
    }
}
