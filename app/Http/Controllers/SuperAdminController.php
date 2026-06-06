<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Usuario;
use App\Models\Mantenimiento;
use App\Models\Solicitud;
use App\Models\Vehiculo;

class SuperAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function perfil()
    {
        $usuario = auth()->user();
        return view('admin.perfilAdmin', compact('usuario'));
    }

    public function index()
    {
        // Tarjetas
        $totalClientes         = Cliente::count();
        $totalTecnicos         = Usuario::where('cod_roles_usu', 3)->count();
        $totalVehiculos        = Vehiculo::count();
        $solicitudesPendientes = Solicitud::where('est_sol', 'Pendiente')->count();

        // Últimas 5 solicitudes
        $ultimasSolicitudes = Solicitud::with('cliente')
            ->orderBy('cod_solicitudes', 'desc')
            ->limit(5)
            ->get();

        // Últimos 5 usuarios
        $ultimosUsuarios = Usuario::with('rol')
            ->orderBy('cod_usuarios', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalClientes',
            'totalTecnicos',
            'totalVehiculos',
            'solicitudesPendientes',
            'ultimasSolicitudes',
            'ultimosUsuarios'
        ));
    }
}