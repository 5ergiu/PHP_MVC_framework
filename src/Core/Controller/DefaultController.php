<?php
namespace App\Core\Controller;

/**
 * Default controller
 */
final class DefaultController extends AbstractController
{
    public function index()
    {
        $this->render(null, 'default');
    }
}