<?php
namespace App\Core\Controller;

/**
 * Default controller
 */
final class DefaultController extends AbstractController
{
    public function index()
    {
//        $this->redirect([
//            'path' => 'auth/login',
//            '?' => [
//                'ceva' => 'test',
//                'incaceva' => 'test2',
//            ],
//            'ext' => 'json'
//        ]);
        $this->render(null, 'default');
    }
}