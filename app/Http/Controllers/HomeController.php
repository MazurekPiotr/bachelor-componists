<?php

namespace App\Http\Controllers;

use Auth;
use JavaScript;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Javascript::put([
            'name' => Auth::user()->name
        ]);
        return view('home');
    }
}
