<?php

use JetBrains\PhpStorm\NoReturn;
use Kint\Kint;

if (!function_exists('dd')) {

    /**
     * Dump and die helper.
     * In this package, Kint dump() method is used.
     */
    #[NoReturn] function dd()
    {
        array_map(function ($param) {
            Kint::dump($param);
        }, func_get_args());
        die(1);
    }
}
