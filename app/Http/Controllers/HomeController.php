<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Porcentaje;

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
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $porcentajes=Porcentaje::all();
            foreach($porcentajes as $p){
                session([$p->nombre_simple => $p->porcentaje/100]);
            }
        return view('home');
    }
}
