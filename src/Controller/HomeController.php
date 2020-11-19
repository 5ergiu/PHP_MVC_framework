<?php

namespace App\Controller;

class HomeController extends AbstractController
{
    public function index()
    {
        $this->render('home/index', [
            'tacac' => 'ceva',
        ]);
    }

    public function mareTest(string $test, int $test2)
    {

        $this->render('home/index');
    }
}
