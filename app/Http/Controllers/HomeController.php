<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')
            ->except('contact');
    }

    public function home(){
        return view('home.index');
    }

    public function contact(){
        return view('home.contact');
    }
}
