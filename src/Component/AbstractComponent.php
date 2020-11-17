<?php
namespace App\Component;

/**
 * @property Session $session The session component.
 */
class AbstractComponent
{
    protected Session $session;

    public function __construct()
    {
        $this->session = new Session;
    }
}
