<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    public function index()
    {
        return view('landing');
    }

    public function header()
    {
        return view('header');
    }

    public function koleksi()
    {
        return view('koleksi');
    }

    public function artikel()
    {
        return view('artikel');
    }

    public function about()
    {
        return view('about');
    }
}
