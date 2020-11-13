<?php

namespace App\Controller;

class HomeController extends AbstractController
{
    public function index()
    {

//        var_dump($test); die;
        $this->render('home', 'index', [
            'tacac' => 'ceva',
        ]);
    }
}