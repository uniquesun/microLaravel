<?php

namespace App\Controller;

use Core\Kernel\Controller;
use Core\Kernel\Request;

class DemoController extends Controller
{
    public function index(Request $request)
    {
        return app('db')->select('select * from users where id = 1');
    }

    public function show()
    {
        app('log')->info('error');
        $tip = 'hello microLaravel';
        return view('index', ['tip' => $tip]);
    }

}