<?php
 
namespace App\Http\Controllers;
 
use App\Models\Cliente;
use App\Models\Vehiculo;
use App\Models\Solicitud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
 
// =====================================================================
// CLIENTE CONTROLLER
// =====================================================================
// CAMBIOS:
// - Antes: $user->cliente daba el modelo Cliente (relacionado por cod_usu).
//   Ahora: Usuario y Cliente son entidades independientes. NO hay relación directa.
//   Para obtener el cliente se debe hacer join por email o guardar cod_clientes en sesión.
//
// SOLUCIÓN ADOPTADA: al hacer login, guardar cod_clientes en sesión (ver LoginController).
// Aquí consultamos el cliente por email_usu == email_cli.
// =====================================================================
class ClienteController extends Controller
{
    /**
     * CAMBIO: antes era $user->cliente (relación Eloquent).
     * Ahora buscamos el cliente por email, ya que no hay FK directa entre usuarios y clientes.
     */
    private function getClienteDelUsuario(): ?Cliente
    {
        $user = Auth::user();
        // Vinculamos por email (email_usu en usuarios = email_cli en clientes)
        return Cliente::where('email_cli', $user->email_usu)->first();
    }
 
    public function misVehiculos()
    {
        $user = Auth::user();
        $cliente = $this->getClienteDelUsuario();
 
        if (!$cliente) {
            return redirect()->route('welcome')
                ->with('error', 'No se encontró tu perfil de cliente.');
        }
 
        // CAMBIO: la FK en vehiculos es "cod_clientes_veh" (antes "cod_cli")
        $vehiculos = $cliente->vehiculos;
 
        return view('client.Cliente-vehiculos', compact('user', 'cliente', 'vehiculos'));
    }
 
    public function misSolicitudes()
    {
        $user = Auth::user();
        $cliente = $this->getClienteDelUsuario();
 
        if (!$cliente) {
            return redirect()->route('welcome')
                ->with('error', 'No se encontró tu perfil de cliente.');
        }
 
        // CAMBIO: la FK en solicitudes es "cod_clientes_sol" (antes "cod_cli")
        $solicitudes = $cliente->solicitudes()
            ->with('vehiculo')
            ->orderBy('cod_solicitudes', 'desc')
            ->get();
 
        return view('client.Cliente-solicitudes', compact('user', 'cliente', 'solicitudes'));
    }
}