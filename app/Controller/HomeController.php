<?php

namespace App\Controller;

use App\Middleware\Authenticate;
use Core\Kernel\Controller;

class HomeController extends Controller
{
    protected $middleware = [
        Authenticate::class
    ];

    public function index()
    {
        return view('welcome');
    }
}