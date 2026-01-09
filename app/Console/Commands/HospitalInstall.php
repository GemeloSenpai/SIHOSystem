<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class HospitalInstall extends Command
{
    protected $signature = 'hospital:install
        {--password= : Password de ejecución (se valida contra HOSPITAL_INSTALL_HASH)}
        {--script= : Ruta a un archivo .sql para importar luego del reset}
        {--only-reset : Solo resetear (no importar script)}
        {--force : Permitir en production}
        {--yes : Confirmar automáticamente sin preguntar}
        {--preserve=* : Tablas adicionales a preservar (ej: categorias,examenes)}
        {--exclude=* : Tablas específicas a excluir del TRUNCATE}';

    protected $description = 'Resetea la BD (preservando tablas especificadas), recrea admin, y opcionalmente importa un SQL.';

    public function handle()
    {
        $this->showHeader();
        
        // 1. Validar entorno de producción
        if (app()->environment('production') && !$this->option('force')) {
            $this->error('❌ Estás en PRODUCTION. Ejecuta con --force si estás totalmente seguro.');
            return self::FAILURE;
        }

        // 2. Validar contraseña
        if (!$this->validatePassword()) {
            $this->error('❌ Password inválido o no proporcionado.');
            return self::FAILURE;
        }

        // 3. Determinar tablas a preservar
        $preserveTables = $this->determinePreserveTables();
        $this->showPreserveInfo($preserveTables);

        // 4. Mostrar advertencia y confirmar
        if (!$this->confirmAction($preserveTables)) {
            $this->info('✅ Operación cancelada por el usuario.');
            return self::SUCCESS;
        }

        // 5. Ejecutar reset
        if (!$this->executeReset($preserveTables)) {
            return self::FAILURE;
        }

        // 6. Importar SQL si se solicita (con exclusión de tablas preservadas)
        if (!$this->importScript($preserveTables)) {
            return self::FAILURE;
        }

        $this->showSuccessMessage();
        return self::SUCCESS;
    }

    /**
     * Mostrar encabezado del comando
     */
    private function showHeader(): void
    {
        $this->newLine();
        $this->line('╔═══════════════════════════════════════════════════════════════╗');
        $this->line('║                    SIHO - INSTALACIÓN/RESET                   ║');
        $this->line('╚═══════════════════════════════════════════════════════════════╝');
        $this->newLine();
    }

    /**
     * Validar contraseña de ejecución
     */
    private function validatePassword(): bool
    {
        $hash = config('hospital_setup.install_hash');
        
        if (!$hash) {
            $this->error('Falta HOSPITAL_INSTALL_HASH en .env');
            $this->line('Ejecuta: php artisan key:generate y configura .env');
            return false;
        }
        
        $plain = $this->option('password') ?: $this->secret('🔑 Password de ejecución');
        
        if (!Hash::check($plain, $hash)) {
            $this->error('Password incorrecto.');
            return false;
        }
        
        return true;
    }

    /**
     * Determinar tablas a preservar
     */
    private function determinePreserveTables(): array
    {
        // Tablas por defecto de la configuración
        $defaultPreserve = collect(config('hospital_setup.preserve_tables', []))
            ->map(fn($t) => strtolower(trim($t)))
            ->values()
            ->all();

        // Tablas desde opción --preserve
        $optionPreserve = collect($this->option('preserve'))
            ->map(fn($t) => strtolower(trim($t)))
            ->values()
            ->all();

        // Tablas desde opción --exclude (alias de --preserve)
        $optionExclude = collect($this->option('exclude'))
            ->map(fn($t) => strtolower(trim($t)))
            ->values()
            ->all();

        // Combinar todas
        $allPreserve = array_unique(array_merge(
            $defaultPreserve,
            $optionPreserve,
            $optionExclude
        ));

        // Ordenar alfabéticamente
        sort($allPreserve);

        return $allPreserve;
    }

    /**
     * Mostrar información de tablas a preservar
     */
    private function showPreserveInfo(array $preserveTables): void
    {
        if (!empty($preserveTables)) {
            $this->info('📋 Tablas que se PRESERVARÁN (no se truncarán):');
            foreach ($preserveTables as $table) {
                $this->line("  ✅ {$table}");
            }
            $this->newLine();
        } else {
            $this->warn('⚠️  No se preservará ninguna tabla. Se truncarán TODAS.');
            $this->newLine();
        }
    }

    /**
     * Confirmar la acción con el usuario
     */
    private function confirmAction(array $preserveTables): bool
    {
        if ($this->option('yes')) {
            return true;
        }

        $preserveCount = count($preserveTables);
        $totalTables = $this->getTotalTableCount();
        $truncateCount = $totalTables - $preserveCount;

        $this->warn('⚠️  ADVERTENCIA CRÍTICA ⚠️');
        $this->line('Esta operación:');
        $this->line("  • TRUNCARÁ {$truncateCount} tablas de {$totalTables}");
        $this->line("  • PRESERVARÁ {$preserveCount} tablas");
        $this->line('  • Eliminará TODOS los datos de las tablas truncadas');
        $this->line('  • Creará un nuevo usuario administrador');
        $this->line('  • Esta acción NO se puede deshacer');
        $this->newLine();

        if (app()->environment('production')) {
            $this->error('¡ESTÁS EN PRODUCCIÓN!');
            if (!$this->confirm('¿Estás ABSOLUTAMENTE SEGURO de continuar?', false)) {
                return false;
            }
            
            return $this->confirm('¿Confirmas por SEGUNDA vez que quieres borrar la base de datos?', false);
        }

        return $this->confirm('¿Continuar con el reset?', false);
    }

    /**
     * Obtener número total de tablas
     */
    private function getTotalTableCount(): int
    {
        try {
            $tables = DB::select('SHOW FULL TABLES WHERE Table_type = "BASE TABLE"');
            return count($tables);
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Ejecutar el reset de la base de datos
     */
    private function executeReset(array $preserveTables): bool
    {
        try {
            // Obtener todas las tablas BASE
            $this->line('📊 Obteniendo lista de tablas...');
            $tables = collect(DB::select('SHOW FULL TABLES WHERE Table_type = "BASE TABLE"'))
                ->map(function ($row) {
                    $props = (array) $row;
                    return strtolower(array_values($props)[0]);
                });

            $this->info("✅ Encontradas {$tables->count()} tablas");
            $this->newLine();

            Schema::disableForeignKeyConstraints();

            // 1) TRUNCATE de TODAS las tablas excepto las preservadas
            $truncated = 0;
            $preserved = 0;
            
            $this->line('🗑️  Iniciando TRUNCATE de tablas...');
            
            foreach ($tables as $table) {
                if (in_array($table, $preserveTables, true)) {
                    $this->line("  ✅ Preservada: {$table}");
                    $preserved++;
                } else {
                    DB::table($table)->truncate();
                    $this->line("  🗑️  Truncada: {$table}");
                    $truncated++;
                }
            }
            
            $this->newLine();
            $this->info("📊 Resumen: {$truncated} tablas truncadas, {$preserved} preservadas");

            // 2) Crear admin por defecto en users (ID=1 tras TRUNCATE)
            if ($tables->contains('users')) {
                $adminCfg = config('hospital_setup.default_admin');
                $adminUserId = DB::table('users')->insertGetId([
                    'name'       => (string) $adminCfg['name'],
                    'email'      => (string) $adminCfg['email'],
                    'password'   => Hash::make((string) $adminCfg['password']),
                    'role'       => 'admin',
                    'estado'     => 'activo',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $this->info("👑 Admin creado: id={$adminUserId}, email={$adminCfg['email']}");
            }

            // 3) Crear empleado vinculado al admin (opcional)
            if ($tables->contains('empleados') && $tables->contains('users')) {
                DB::table('empleados')->insert([
                    'user_id'   => 1,
                    'nombre'    => 'Admin',
                    'apellido'  => 'Sistema',
                    'edad'      => 30,
                    'dni'       => '0000000000000',
                    'sexo'      => 'M',
                    'direccion' => 'S/N',
                    'telefono'  => '00000000',
                ]);
                $this->info("👥 Empleado vinculado al admin (user_id=1)");
            }

            Schema::enableForeignKeyConstraints();
            
            return true;

        } catch (\Exception $e) {
            $this->error('❌ Error durante el reset: ' . $e->getMessage());
            Schema::enableForeignKeyConstraints();
            return false;
        }
    }

    /**
     * Importar script SQL si se especifica
     */
    private function importScript(array $preserveTables): bool
    {
        if ($this->option('only-reset')) {
            return true;
        }

        $script = $this->option('script');
        if (!$script) {
            return true;
        }

        if (!is_file($script)) {
            $this->error("❌ No encuentro el archivo SQL: {$script}");
            return false;
        }

        $this->newLine();
        $this->line("📥 Preparando importación de: {$script}");
        $fileSize = $this->formatBytes(filesize($script));
        $this->line("📄 Tamaño del archivo: {$fileSize}");

        // Si hay tablas preservadas, advertir sobre posibles duplicados
        if (!empty($preserveTables)) {
            $this->warn("⚠️  Advertencia: Las siguientes tablas están preservadas:");
            foreach ($preserveTables as $table) {
                $this->line("  • {$table}");
            }
            $this->line("Si el archivo SQL contiene datos para estas tablas, pueden ocurrir errores de duplicado.");
            $this->newLine();
        }

        if (!$this->option('yes') && !$this->confirm("¿Importar {$script} ahora?", true)) {
            $this->info('✅ Importación cancelada.');
            return true;
        }

        try {
            $this->line('🔄 Importando datos...');
            
            $sql = file_get_contents($script);
            if (empty($sql)) {
                $this->error('❌ El archivo SQL está vacío.');
                return false;
            }

            // Si hay tablas preservadas, procesar SQL para excluirlas
            if (!empty($preserveTables)) {
                $sql = $this->excludeTablesFromSQL($sql, $preserveTables);
                $this->info('✅ SQL procesado para excluir tablas preservadas.');
            }

            // Ejecutar en transacción
            DB::beginTransaction();
            DB::unprepared($sql);
            DB::commit();

            $this->info('✅ Import SQL finalizado exitosamente.');
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('❌ Error durante la importación: ' . $e->getMessage());
            
            // Verificar si es error de duplicado
            if (str_contains($e->getMessage(), 'Duplicate entry') || 
                str_contains($e->getMessage(), '1062')) {
                $this->line('💡 Sugerencia: Usa --preserve para excluir tablas que causan duplicados');
            }
            
            return false;
        }
    }

    /**
     * Excluir tablas preservadas del SQL
     */
    private function excludeTablesFromSQL(string $sql, array $excludeTables): string
    {
        $this->line('🔧 Procesando SQL para excluir tablas preservadas...');
        
        // Dividir el SQL en instrucciones individuales
        $statements = array_filter(array_map('trim', explode(';', $sql)));
        $filteredStatements = [];
        
        foreach ($statements as $statement) {
            $skip = false;
            
            // Verificar si la declaración afecta una tabla excluida
            foreach ($excludeTables as $table) {
                // Patrones comunes para identificar tablas en SQL
                $patterns = [
                    "/INSERT\s+INTO\s+`?{$table}`?\s+/i",
                    "/INSERT\s+INTO\s+`?{$table}`?\(/i",
                    "/UPDATE\s+`?{$table}`?\s+/i",
                    "/DELETE\s+FROM\s+`?{$table}`?\s+/i",
                    "/DROP\s+TABLE\s+(IF\s+EXISTS\s+)?`?{$table}`?/i",
                    "/CREATE\s+TABLE\s+(IF\s+NOT\s+EXISTS\s+)?`?{$table}`?/i",
                    "/TRUNCATE\s+TABLE\s+`?{$table}`?/i",
                    "/ALTER\s+TABLE\s+`?{$table}`?/i",
                ];
                
                foreach ($patterns as $pattern) {
                    if (preg_match($pattern, $statement)) {
                        $this->line("  ⏭️  Excluyendo declaración para tabla: {$table}");
                        $skip = true;
                        break 2;
                    }
                }
            }
            
            if (!$skip) {
                $filteredStatements[] = $statement;
            }
        }
        
        return implode(";\n", $filteredStatements) . ';';
    }

    /**
     * Mostrar mensaje de éxito
     */
    private function showSuccessMessage(): void
    {
        $this->newLine();
        $this->line('╔═══════════════════════════════════════════════════════════════╗');
        $this->line('║                     OPERACIÓN COMPLETADA                      ║');
        $this->line('╚═══════════════════════════════════════════════════════════════╝');
        $this->newLine();
        
        $adminCfg = config('hospital_setup.default_admin');
        
        $this->table(['Credenciales de acceso', 'Valor'], [
            ['Email', $adminCfg['email']],
            ['Contraseña', $adminCfg['password']],
            ['URL de acceso', url('/')],
        ]);
        
        $this->newLine();
        $this->info('🎉 Sistema SIHO listo para usar.');
        $this->line('Recomendación: Cambia la contraseña del administrador después del primer acceso.');
    }

    /**
     * Formatear bytes para mostrar tamaño de archivo
     */
    private function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}