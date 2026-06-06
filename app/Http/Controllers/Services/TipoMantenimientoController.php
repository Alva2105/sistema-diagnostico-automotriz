<?php

namespace App\Http\Controllers\Services;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TipoMantenimientoController extends Controller
{
        /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'mantPreventivo', 'mantCorrectivo']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('welcome');
    }

    public function mantPreventivo()
    {
        return view('services.mantPreventivo');
    }

    public function mantCorrectivo()
    {
        return view('services.mantCorrectivo');
    }
}