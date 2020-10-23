<?php

namespace App\Controller;

use App\Core\Controller;

class HomeController extends Controller
{
    public function index()
    {
//        if ($this->request->is('post')) {
//            var_dump($this->request->data); die;
//        }
        $this->render('home', 'index', [
            'tacac' => 'ceva',
        ]);
    }
}