<?php

namespace App\Controllers;

use Luany\Core\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request): string
    {
        return view('pages.home', [
            'title' => env('APP_NAME', 'Luany'),
        ]);
    }
}