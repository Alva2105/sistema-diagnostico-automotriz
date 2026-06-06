<?php
 
namespace App\Http\Controllers;
 
use App\Models\Asignacion;
use App\Models\Solicitud;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
 
class AsignacionController extends Controller
{
    /**
     * Guarda una nueva asignación de técnico a solicitud.
     * POST /dashboard/asignaciones/guardar
     */
    public function guardar(Request $request)
    {
        $request->validate([
            'cod_solicitudes_asi' => 'required|exists:solicitudes,cod_solicitudes',
            'cod_usuarios_asi'    => 'required|exists:usuarios,cod_usuarios',
            'obs_asi'             => 'nullable|string',
        ]);
 
        // El trigger de la BD genera el cod_asignaciones automáticamente (ASI001, ...)
        // pero como Laravel no lo sabe, insertamos sin ese campo y dejamos que el trigger actúe.
        // Si tu trigger está en BEFORE INSERT, basta con no enviar cod_asignaciones.
 
        DB::table('asignaciones')->insert([
            'fec_asi'             => now()->toDateString(),   // fecha de hoy, no editable
            'obs_asi'             => $request->obs_asi,
            'cod_solicitudes_asi' => $request->cod_solicitudes_asi,
            'cod_usuarios_asi'    => $request->cod_usuarios_asi,
        ]);
 
        return redirect()->back()->with('success', 'Técnico asignado correctamente.');
    }
 
    /**
     * (Opcional) Devuelve en JSON si una solicitud ya tiene asignación,
     * útil para que el modal muestre el técnico actual al abrirse.
     * GET /dashboard/asignaciones/solicitud/{id}
     */
    public function porSolicitud(string $id)
    {
        $asignacion = Asignacion::where('cod_solicitudes_asi', $id)
            ->with('tecnico')
            ->latest('fec_asi')
            ->first();
 
        return response()->json($asignacion);
    }
}