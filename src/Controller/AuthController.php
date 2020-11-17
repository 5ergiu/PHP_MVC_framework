<?php
namespace App\Controller;

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
     * just some text here
     * and here
     * @param int|null $test mare test.
     * @throws Exception
     */
    public function login(?int $test = null)
    {
        if ($this->request->is('post')) {
            $this->loadRepo('users');
            $user = $this->UsersRepo->findBy('username', $this->request->data['username']);
//            var_dump($user); die;
            $this->newJsonResponse($user);
        }
    }

    public function register()
    {
        $User = new User;
        $this->loadRepo('users');
        if ($this->request->is('post')) {
            if ($this->UsersRepo->save($User, $this->request->data)) {
                var_dump($this->UsersRepo->lastInsertedId()); die;
            }
        }
        $this->render('auth/register', [
            'User' => $User,
        ]);
    }
}
