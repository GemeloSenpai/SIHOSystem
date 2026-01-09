<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Registra comandos de consola de tu app.
     */
    protected $commands = [
        \App\Console\Commands\HospitalInstall::class,
        \App\Console\Commands\HospitalBackup::class,
        \App\Console\Commands\HospitalRestore::class,
        // \App\Console\Commands\OtroComando::class,
    ];

    /**
     * Define tareas programadas (si necesitas cron).
     */
    protected function schedule(Schedule $schedule): void
    {
        // Backup diario automático (2 AM) - Solo si existe token de cron
        if (config('hospital_setup.cron_token')) {
            $schedule->command('hospital:backup --compress --keep=7 --silent')
                ->dailyAt('02:00')
                ->name('siho-daily-backup')
                ->appendOutputTo(storage_path('logs/backups/daily.log'))
                ->emailOutputOnFailure(config('hospital_setup.default_admin.email'))
                ->withoutOverlapping()
                ->runInBackground();
        }
    }

    /**
     * Carga automáticamente los comandos del directorio y el routes/console.php
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        // Rutas de consola (opcional, pero recomendado)
        if (file_exists(base_path('routes/console.php'))) {
            require base_path('routes/console.php');
        }
    }
}