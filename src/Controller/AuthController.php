<?php

namespace App\Controller;

use App\Core\Controller;
use App\Model\Entity\User;

class AuthController extends Controller
{
    public function login()
    {
        if ($this->request->is('post')) {
            $user = new User();
            $user->bindValues($this->request->data);
            $errors = $user->validate();
            if (!empty($errors)) {
                var_dump($errors); die;
            }
        }
        $this->render('auth', 'login');
    }
}