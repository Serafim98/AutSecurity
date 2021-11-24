<?php

namespace App\Http\Controllers;


use App\Models\Seguradora;
use App\Models\Seguro;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home(){
        $produtos = Seguro::all();
        return view('welcome', compact('produtos'));
    }
}
