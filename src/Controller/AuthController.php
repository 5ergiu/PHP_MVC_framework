<?php
namespace App\Controller;

use App\Core\Controller\AbstractController;
use App\Model\Entity\User;
use App\Model\Repository\UserRepository;

/**
 * @property User $User;
 */
class AuthController extends AbstractController
{
    public function login()
    {
        $User = new User;
        $UserRepo = new UserRepository;
//        var_dump($UserRepo->findBy('test', 'test', 'test'));
        if ($this->request->is('post')) {
            $User->bindValues($this->request->data);
            if ($User->save()) {
                var_dump('s-a salvat');
            }
        }
        $this->render('auth', 'login', [
            'User' => $User,
        ]);
    }

    public function index(){}
}