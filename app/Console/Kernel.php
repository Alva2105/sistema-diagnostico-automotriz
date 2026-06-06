<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            $usuarios = \App\Models\Usuario::where('est_usu', 'Activo')
                ->where('last_login', '<', now()->subDays(7))
                ->get();

            foreach ($usuarios as $usuario) {
                $usuario->update(['est_usu' => 'Inactivo']);
            }
        })->daily(); // se ejecuta cada día
    }

    protected $middlewareGroups = [
        'web' => [
            // ...
            \App\Http\Middleware\VerificarEstadoUsuario::class,
        ],
    ];
    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
    
}
