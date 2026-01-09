<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class HospitalRestore extends Command
{
    protected $signature = 'hospital:restore
        {file : Archivo .sql o .sql.gz a restaurar}
        {--password= : Password de ejecución (se valida contra HOSPITAL_INSTALL_HASH)}
        {--exclude=* : Tablas a excluir de la restauración (ej: categorias,examenes)}
        {--test : Solo probar, no restaurar realmente}
        {--force : Permitir en production}
        {--yes : Confirmar automáticamente}';

    protected $description = 'Restaura la base de datos SIHO desde un backup';

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

        // 3. Verificar archivo
        $file = $this->argument('file');
        if (!$this->validateFile($file)) {
            return self::FAILURE;
        }

        // 4. Mostrar información
        $this->showRestoreInfo($file);

        // 5. Confirmación
        if (!$this->confirmRestore()) {
            $this->info('✅ Restauración cancelada.');
            return self::SUCCESS;
        }

        // 6. Modo prueba
        if ($this->option('test')) {
            return $this->runTestMode($file);
        }

        // 7. Ejecutar restauración
        return $this->executeRestore($file);
    }

    /**
     * Mostrar encabezado
     */
    private function showHeader(): void
    {
        $this->newLine();
        $this->line('╔═══════════════════════════════════════════════════════════════╗');
        $this->line('║                    SIHO - RESTAURACIÓN                        ║');
        $this->line('╚═══════════════════════════════════════════════════════════════╝');
        $this->newLine();
    }

    /**
     * Validar contraseña
     */
    private function validatePassword(): bool
    {
        $hash = config('hospital_setup.install_hash');
        
        if (!$hash) {
            $this->error('Falta HOSPITAL_INSTALL_HASH en .env');
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
     * Validar archivo de backup
     */
    private function validateFile(string $file): bool
    {
        // Verificar que existe
        if (!file_exists($file)) {
            $this->error("❌ El archivo no existe: {$file}");
            return false;
        }

        // Verificar que sea legible
        if (!is_readable($file)) {
            $this->error("❌ No se puede leer el archivo: {$file}");
            $this->line('Verifica los permisos del archivo.');
            return false;
        }

        // Verificar extensión
        $allowedExtensions = ['sql', 'gz'];
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        
        if (!in_array($ext, $allowedExtensions)) {
            $this->error("❌ Extensión no válida: .{$ext}");
            $this->line('Solo se permiten archivos .sql o .sql.gz');
            return false;
        }

        // Verificar tamaño
        $filesize = filesize($file);
        if ($filesize === 0) {
            $this->error('❌ El archivo está vacío.');
            return false;
        }

        return true;
    }

    /**
     * Mostrar información de restauración
     */
    private function showRestoreInfo(string $file): void
    {
        $config = config('database.connections.mysql');
        $filesize = filesize($file);
        $sizeFormatted = $this->formatBytes($filesize);
        $modified = date('Y-m-d H:i', filemtime($file));
        
        $this->warn('📋 INFORMACIÓN DE RESTAURACIÓN');
        $this->newLine();
        
        $info = [
            ['📁 Archivo', basename($file)],
            ['📂 Ruta', dirname($file)],
            ['💾 Tamaño', $sizeFormatted],
            ['🕐 Modificado', $modified],
            ['🗃️ Base de datos destino', $config['database']],
            ['📍 Host', $config['host']],
            ['👤 Usuario', $config['username']],
        ];
        
        // Mostrar tablas excluidas si las hay
        $excludeTables = $this->option('exclude');
        if (!empty($excludeTables)) {
            $info[] = ['🚫 Tablas excluidas', implode(', ', $excludeTables)];
        }
        
        $this->table(['Parámetro', 'Valor'], $info);
    }

    /**
     * Confirmar restauración
     */
    private function confirmRestore(): bool
    {
        if ($this->option('yes')) {
            return true;
        }

        $this->newLine();
        $this->error('⚠️  ADVERTENCIA CRÍTICA ⚠️');
        $this->line('Esta operación:');
        $this->line('  • SOBREESCRIBIRÁ la base de datos actual');
        $this->line('  • Eliminará TODOS los datos actuales');
        $this->line('  • Restaurará los datos del backup');
        $this->line('  • Esta acción NO se puede deshacer');
        $this->newLine();

        if (app()->environment('production')) {
            $this->error('¡ESTÁS EN PRODUCCIÓN!');
            if (!$this->confirm('¿Estás ABSOLUTAMENTE SEGURO de continuar?', false)) {
                return false;
            }
            
            return $this->confirm('¿Confirmas por SEGUNDA vez que quieres restaurar este backup?', false);
        }

        return $this->confirm('¿Continuar con la restauración?', false);
    }

    /**
     * Modo prueba
     */
    private function runTestMode(string $file): int
    {
        $this->info('🔧 MODO PRUEBA - No se restaurará realmente');
        
        $command = $this->buildRestoreCommand($file);
        $config = config('database.connections.mysql');
        
        $this->line('Comando que se ejecutaría:');
        $this->line(str_replace($config['password'], '***', $command));
        
        $this->newLine();
        $this->info('✅ Prueba completada. Ejecuta sin --test para restaurar realmente.');
        
        return self::SUCCESS;
    }

    /**
     * Ejecutar restauración
     */
    private function executeRestore(string $file): int
    {
        $this->newLine();
        $this->line('🚀 Iniciando restauración...');
        
        // Procesar archivo si hay tablas excluidas
        $excludeTables = $this->option('exclude');
        if (!empty($excludeTables)) {
            $processedFile = $this->processFileWithExclusions($file, $excludeTables);
            if (!$processedFile) {
                $this->error('❌ No se pudo procesar el archivo con las exclusiones.');
                return self::FAILURE;
            }
            $file = $processedFile;
            $this->info('✅ Archivo procesado con exclusiones aplicadas.');
        }
        
        $command = $this->buildRestoreCommand($file);
        $process = Process::fromShellCommandline($command);
        $process->setTimeout(600); // 10 minutos
        
        $bar = $this->output->createProgressBar(100);
        $bar->start();
        
        try {
            $process->mustRun(function ($type, $buffer) use ($bar) {
                if ($buffer && $bar->getProgress() < 95) {
                    $bar->advance(1);
                }
            });
            
            $bar->finish();
            $this->newLine();
            $this->info('✅ Restauración completada exitosamente!');
            
            // Limpiar cache de Laravel
            $this->cleanCache();
            
            // Eliminar archivo temporal si se creó uno
            if ($this->option('exclude') && file_exists($file) && str_contains($file, 'temp_restore_')) {
                unlink($file);
                $this->line('🗑️  Archivo temporal eliminado.');
            }
            
            return self::SUCCESS;
            
        } catch (\Exception $e) {
            $this->error('❌ Error en la restauración: ' . $e->getMessage());
            if ($process->getErrorOutput()) {
                $this->error('Detalles: ' . $process->getErrorOutput());
            }
            
            // Limpiar archivo temporal si existe
            if (file_exists($file) && str_contains($file, 'temp_restore_')) {
                unlink($file);
            }
            
            return self::FAILURE;
        }
    }

    /**
     * Procesar archivo excluyendo tablas específicas
     */
    private function processFileWithExclusions(string $file, array $excludeTables): string
    {
        $this->line('🔧 Procesando archivo para excluir tablas...');
        
        try {
            // Leer contenido del archivo
            if (pathinfo($file, PATHINFO_EXTENSION) === 'gz') {
                $content = shell_exec("gunzip -c " . escapeshellarg($file));
            } else {
                $content = file_get_contents($file);
            }
            
            if (empty($content)) {
                throw new \Exception('El archivo está vacío o no se puede leer');
            }
            
            // Excluir tablas del contenido
            $processedContent = $this->excludeTablesFromSQL($content, $excludeTables);
            
            // Crear archivo temporal
            $tempFile = storage_path('app/temp_restore_' . uniqid() . '.sql');
            file_put_contents($tempFile, $processedContent);
            
            $this->info("✅ Archivo procesado. Tablas excluidas: " . implode(', ', $excludeTables));
            
            return $tempFile;
            
        } catch (\Exception $e) {
            $this->error('❌ Error procesando archivo: ' . $e->getMessage());
            return $file; // Devolver archivo original
        }
    }

    /**
     * Excluir tablas del SQL
     */
    private function excludeTablesFromSQL(string $sql, array $excludeTables): string
    {
        $excludeTables = array_map('strtolower', $excludeTables);
        
        // Dividir el SQL en instrucciones individuales
        $statements = array_filter(array_map('trim', explode(';', $sql)));
        $filteredStatements = [];
        
        foreach ($statements as $statement) {
            $skip = false;
            
            foreach ($excludeTables as $table) {
                // Patrones para identificar operaciones en tablas excluidas
                $patterns = [
                    "/INSERT\s+(IGNORE\s+)?INTO\s+`?{$table}`?\s+/i",
                    "/INSERT\s+(IGNORE\s+)?INTO\s+`?{$table}`?\(/i",
                    "/UPDATE\s+(IGNORE\s+)?`?{$table}`?\s+/i",
                    "/DELETE\s+(IGNORE\s+)?FROM\s+`?{$table}`?\s+/i",
                    "/DROP\s+TABLE\s+(IF\s+EXISTS\s+)?`?{$table}`?/i",
                    "/CREATE\s+TABLE\s+(IF\s+NOT\s+EXISTS\s+)?`?{$table}`?/i",
                    "/TRUNCATE\s+(TABLE\s+)?`?{$table}`?/i",
                    "/ALTER\s+TABLE\s+`?{$table}`?/i",
                ];
                
                foreach ($patterns as $pattern) {
                    if (preg_match($pattern, $statement)) {
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
     * Construir comando de restauración
     */
    private function buildRestoreCommand(string $file): string
    {
        $config = config('database.connections.mysql');
        $parts = [];
        
        // Si es archivo comprimido
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        if ($ext === 'gz') {
            $parts[] = 'gunzip -c';
            $parts[] = escapeshellarg($file);
            $parts[] = '|';
            $parts[] = 'mysql';
        } else {
            $parts[] = 'mysql';
        }
        
        // Credenciales
        $parts[] = "--host={$config['host']}";
        $parts[] = "--user={$config['username']}";
        $parts[] = "--password={$config['password']}";
        
        if (isset($config['port']) && $config['port']) {
            $parts[] = "--port={$config['port']}";
        }
        
        $parts[] = $config['database'];
        
        // Si no es comprimido, agregar redirección
        if ($ext !== 'gz') {
            $parts[] = '<';
            $parts[] = escapeshellarg($file);
        }
        
        return implode(' ', $parts);
    }

    /**
     * Limpiar cache después de restauración
     */
    private function cleanCache(): void
    {
        $this->line('🧹 Limpiando caché del sistema...');
        
        $commands = [
            'cache:clear',
            'config:clear',
            'view:clear',
            'route:clear',
            'optimize:clear',
        ];
        
        foreach ($commands as $command) {
            $this->call($command);
        }
        
        $this->info('♻️  Caché del sistema limpiada.');
    }

    /**
     * Formatear bytes
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