<?php

namespace App\Controller;

use App\Core\Controller\AbstractController;

class HomeController extends AbstractController
{
    public function index()
    {
        $this->render('home', 'index', [
            'tacac' => 'ceva',
        ]);
    }
}