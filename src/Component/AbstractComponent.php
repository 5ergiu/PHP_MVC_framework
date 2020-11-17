<?php
namespace App\Component;

/**
 * @property SessionComponent $session The session component.
 */
class AbstractComponent
{
    protected SessionComponent $session;

    public function __construct()
    {
        $this->session = new SessionComponent;
    }
}
