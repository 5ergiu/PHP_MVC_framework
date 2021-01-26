<?php


namespace App\Controller;


class FooController extends AbstractController
{
    public function test(int $id, string $parametru)
    {
        var_dump($this->request->query->all()); die;
        var_dump($parametru, $id); die;
    }
}

