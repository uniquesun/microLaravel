<?php

namespace Core\Kernel;

use duncan3dc\Laravel\BladeInstance;

class Blade
{

    protected $template;

    public function __construct()
    {
        $config = app('config')->get('view');
        $this->template = new BladeInstance($config['view_path'], $config['cache_path']);
    }


    public function render($path, $params = [])
    {
        return $this->template->render($path, $params);
    }


}