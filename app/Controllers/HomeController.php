<?php

namespace App\Controllers;

class HomeController extends Controller
{
    public function index(): string
    {
        return view('pages.home');
    }
}
