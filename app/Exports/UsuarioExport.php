<?php

namespace App\Exports;

use App\Models\Usuario;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class UsuariosExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    ShouldAutoSize
{
    public function collection()
    {
        return Usuario::with('rol')
            ->orderBy('cod_usuarios', 'asc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Nombre Completo',
            'Correo Electrónico',
            'Rol',
            'Fecha de Exportación'
        ];
    }

    public function map($usuario): array
    {
        return [
            trim(
                $usuario->nom_usu . ' ' .
                $usuario->app_usu . ' ' .
                ($usuario->apm_usu ?? '')
            ),

            $usuario->email_usu,

            $usuario->rol->nom_rol ?? 'Sin rol',

            now()->format('d/m/Y H:i')
        ];
    }
}