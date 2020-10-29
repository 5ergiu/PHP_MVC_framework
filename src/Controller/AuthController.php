<?php
namespace App\Controller;

use App\Core\Controller\AbstractController;
use App\Model\Entity\User;
use App\Model\Repository\UserRepository;
use Exception;
/**
 * @property User $User;
 */
class AuthController extends AbstractController
{
    /**
     * @throws Exception
     */
    public function login()
    {
        $User = new User;
        $UserRepo = new UserRepository;
//        var_dump($UserRepo); die;
        $test = $UserRepo->findByExample('sergiu');
//        var_dump($test); die;
        if ($this->request->is('post')) {
            $User->bindValues($this->request->data);
            $test = $UserRepo->save($User);
            var_dump($test); die;
        }
        $this->render('auth', 'login', [
            'User' => $User,
        ]);
    }

    public function index(){}
}