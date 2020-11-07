<?php

namespace App\Controller;

use App\Core\Controller\Controller;

class HomeController extends Controller
{
    public function index()
    {

//        var_dump($test); die;
        $this->render('home', 'index', [
            'tacac' => 'ceva',
        ]);
    }
}