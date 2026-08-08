<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MainController extends Controller
{
    public function index()
    {
        $view = 'dashboard';
        return view('pages.dashboard', compact('view'));
    }
}
