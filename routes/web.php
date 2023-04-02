<?php

use Core\Kernel\Router;


Router::get('/', 'HomeController@index');

Router::get('/hello', function () {
    return 'hello world';
});


Router::get('users', 'DemoController@index');
Router::get('blade', 'DemoController@show');

