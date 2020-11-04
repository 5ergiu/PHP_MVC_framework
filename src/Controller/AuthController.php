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
     * just some text here
     * and here
     * @param int|null $test mare test.
     * @throws Exception
     */
    public function login(?int $test = null)
    {
        $User = new User;
        $UserRepo = new UserRepository;
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