<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Vehiculo;
use App\Models\Solicitud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * CAMBIOS RESPECTO AL ORIGINAL:
 * - pla_veh NO existe en la BD. La placa ES el cod_vehiculos (varchar 8).
 * - PK COMPUESTA: (cod_vehiculos, cod_clientes_veh). Siempre se usan ambos.
 * - Se ELIMINA Vehiculo::siguienteCodigo() (fue eliminado del model).
 * - store(): guarda la placa directamente en cod_vehiculos.
 * - guardar(): valida cod_vehiculos como unique en lugar de pla_veh.
 * - actualizar(): recibe $codVehiculo y $codCliente (PK compuesta).
 * - eliminar(): recibe $codVehiculo y $codCliente (PK compuesta).
 * - buscar(): filtra por cod_vehiculos en lugar de pla_veh.
 */
class VehiculoController extends Controller
{
    public function create()
    {
        return view('client.registroAuto');
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'placa'  => 'required|string|max:8|unique:vehiculos,cod_vehiculos',
                'tipo'   => 'nullable|string|max:50',
                'marca'  => 'nullable|string|max:50',
                'modelo' => 'nullable|string|max:50',
                'anio'   => 'nullable|integer|min:1950|max:2030',
                'color'  => 'nullable|string|max:30',
            ]);

            $user    = auth()->user();
            $cliente = Cliente::where('email_cli', $user->email_usu)->first();

            if (!$cliente) {
                return redirect()->back()
                    ->withErrors(['cliente' => 'No se pudo identificar al cliente.'])
                    ->withInput();
            }

            Vehiculo::create([
                'cod_vehiculos'    => strtoupper($request->placa), // La placa ES el cod_vehiculos
                'cod_clientes_veh' => $cliente->cod_clientes,       // CI del cliente (parte 2 de PK)
                'mar_veh'          => $request->marca,
                'mod_veh'          => $request->modelo,
                'tip_veh'          => $request->tipo,
                'ani_veh'          => $request->anio,
                'col_veh'          => $request->color,
                // ELIMINADOS: pla_veh (no existe), cod_con, est_veh
            ]);

            return redirect()->route('cliente.vehiculos')
                ->with('success', 'Vehículo registrado correctamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Error: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function guardar(Request $request)
    {
        $request->validate([
            'cod_vehiculos'    => 'required|string|max:8|unique:vehiculos,cod_vehiculos',
            'mar_veh'          => 'required|string|max:50',
            'mod_veh'          => 'required|string|max:50',
            'ani_veh'          => 'nullable|integer|min:1950|max:2030',
            'col_veh'          => 'nullable|string|max:30',
            'tip_veh'          => 'nullable|string|max:50',
            'cod_clientes_veh' => 'required|exists:clientes,cod_clientes',
        ]);

        Vehiculo::create([
            'cod_vehiculos'    => strtoupper($request->cod_vehiculos),
            'cod_clientes_veh' => $request->cod_clientes_veh,
            'mar_veh'          => $request->mar_veh,
            'mod_veh'          => $request->mod_veh,
            'ani_veh'          => $request->ani_veh,
            'col_veh'          => $request->col_veh,
            'tip_veh'          => $request->tip_veh,
        ]);

        return redirect()->back()->with('success', 'Vehículo registrado correctamente.');
    }

    /**
     * PK compuesta: siempre se necesitan ambos parámetros.
     * cod_vehiculos (placa) es la PK, no se puede modificar.
     * Solo se actualizan los datos del vehículo (marca, modelo, etc.).
     */
    public function actualizar(Request $request, $codVehiculo, $codCliente)
    {
        $vehiculo = Vehiculo::where('cod_vehiculos', $codVehiculo)
            ->where('cod_clientes_veh', $codCliente)
            ->firstOrFail();

        $request->validate([
            'mar_veh' => 'required|string|max:50',
            'mod_veh' => 'required|string|max:50',
            'ani_veh' => 'nullable|integer|min:1950|max:2030',
            'col_veh' => 'nullable|string|max:30',
            'tip_veh' => 'nullable|string|max:50',
        ]);

        $vehiculo->update([
            'mar_veh' => $request->mar_veh,
            'mod_veh' => $request->mod_veh,
            'ani_veh' => $request->ani_veh,
            'col_veh' => $request->col_veh,
            'tip_veh' => $request->tip_veh,
            // cod_vehiculos (placa) NO se actualiza: es parte de la PK
        ]);

        return redirect()->back()->with('success', 'Vehículo actualizado correctamente.');
    }

    /**
     * PK compuesta: siempre se necesitan ambos parámetros para eliminar.
     */
    public function eliminar($codVehiculo, $codCliente)
    {
        $vehiculo = Vehiculo::where('cod_vehiculos', $codVehiculo)
            ->where('cod_clientes_veh', $codCliente)
            ->first();

        if (!$vehiculo) {
            return redirect()->back()->with('error', 'Vehículo no encontrado.');
        }

        try {
            // Eliminar solicitudes relacionadas primero
            \DB::table('solicitudes')
                ->where('cod_vehiculos_sol', $codVehiculo)
                ->where('cod_clientes_sol', $codCliente)
                ->delete();

            // Eliminar vehículo usando ambas partes de la PK compuesta
            \DB::table('vehiculos')
                ->where('cod_vehiculos', $codVehiculo)
                ->where('cod_clientes_veh', $codCliente)
                ->delete();

            return redirect()->back()->with('success', 'Vehículo eliminado correctamente.');

        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->back()->with('error', 'ERROR: ' . $e->getMessage());
        }
    }

    public function listarVehiculos()
    {
        $vehiculos = Vehiculo::with('cliente')
            ->orderBy('cod_vehiculos', 'asc')
            ->paginate(10);

        $tipo = 'Vehículos';
        return view('admin.dashboardVehiculos', compact('vehiculos', 'tipo'));
    }

    /**
     * est_veh no existe en la nueva BD.
     */
    public function actualizarEstado(Request $request, $id)
    {
        return response()->json([
            'success' => false,
            'message' => 'El campo est_veh no existe en la nueva estructura de BD.'
        ], 422);
    }

    /**
     * Antes filtraba por pla_veh (no existe).
     * Ahora filtra por cod_vehiculos (la placa).
     */
    public function buscar(Request $request)
    {
        $query = $request->input('q');

        $vehiculos = Vehiculo::with('cliente')
            ->where('cod_vehiculos', 'ILIKE', "%{$query}%") // La placa es cod_vehiculos
            ->orWhere('mar_veh',     'ILIKE', "%{$query}%")
            ->orWhere('mod_veh',     'ILIKE', "%{$query}%")
            ->orWhere('col_veh',     'ILIKE', "%{$query}%")
            ->orWhere('tip_veh',     'ILIKE', "%{$query}%")
            ->orWhereHas('cliente', function ($q) use ($query) {
                $q->where('nom_cli',  'ILIKE', "%{$query}%")
                  ->orWhere('app_cli', 'ILIKE', "%{$query}%")
                  ->orWhere('apm_cli', 'ILIKE', "%{$query}%");
            })
            ->get();

        return response()->json($vehiculos);
    }
}