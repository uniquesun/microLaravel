<?php

define('PHP_START', microtime(true));

//error_reporting(E_ERROR | E_WARNING);

const PHP_PATH = __DIR__;

require PHP_PATH . '/../vendor/autoload.php';

$app = require_once PHP_PATH . '/../bootstrap/app.php';

// request
$app->bind('request', function () {
    return \Core\Kernel\Request::create($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD'], $_SERVER);
});


// 响应
app('response')->setContent(
// 由路由分发处理的请求
    app('router')->dispatch(
    // 请求
        app('request')
    )
)->send();
