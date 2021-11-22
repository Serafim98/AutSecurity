<?php

namespace App\Http\Controllers;


use App\Models\Seguradora;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home(){
        $seguradoras = Seguradora::all();
        return view('welcome', compact('seguradoras'));
    }
}
