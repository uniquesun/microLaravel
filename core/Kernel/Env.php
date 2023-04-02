<?php

namespace Core\Kernel;

use Symfony\Component\Dotenv\Dotenv;

class Env
{
    public function init()
    {
        $dotenv = new Dotenv();
        $dotenv->load(PHP_PATH . '/../.env');
    }
}