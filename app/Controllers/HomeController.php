<?php

namespace App\Controllers;

use Luany\Core\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request): string
    {
        return view('pages.home', [
            'title' => env('APP_NAME', 'Luany'),
            'users' => [
                ['name' => 'António Ngola'],
                ['name' => 'Luany António'],
                ['name' => 'Kelson Filipe'],
                ['name' => 'Adário Muatelembe'],
            ],
            'unsafe' => '<script>alert("xss")</script>',
            'html'   => '<strong style="color:var(--luany-orange)">Bold HTML</strong> rendered raw',
        ]);
    }
}