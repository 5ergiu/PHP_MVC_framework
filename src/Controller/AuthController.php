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
        $User = new User;
        $this->loadRepo('users');
        var_dump($this->UsersRepo->findByExample('sergiu')); die;
        if ($this->request->is('post')) {
            $User->bindValues($this->request->data);
            $test = $UserRepo->save($User);
            var_dump($test); die;
        }
        $this->render('auth', 'login', [
            'User' => $User,
        ]);
    }
}