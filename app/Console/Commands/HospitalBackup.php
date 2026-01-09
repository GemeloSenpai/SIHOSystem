<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class HospitalBackup extends Command
{
    protected $signature = 'hospital:backup
        {--password= : Password de ejecución (se valida contra HOSPITAL_INSTALL_HASH)}
        {--path= : Ruta para guardar backup (default: storage/backups/siho)}
        {--filename= : Nombre personalizado del archivo}
        {--compress : Comprimir con gzip}
        {--keep= : Mantener solo los últimos N backups}
        {--tables= : Tablas específicas a respaldar}
        {--exclude=* : Tablas a excluir del backup (ej: categorias,examenes)}
        {--no-data : Solo estructura, sin datos}
        {--only-data : Solo datos, sin estructura}
        {--test : Solo probar, no ejecutar}
        {--force : Permitir en production}
        {--yes : Confirmar automáticamente}
        {--silent : Modo silencioso para cron}';

    protected $description = 'Crea un backup seguro de la base de datos SIHO';

    public function handle()
    {
        if (!$this->option('silent')) {
            $this->showHeader();
        }

        // 1. Validar entorno de producción
        if (app()->environment('production') && !$this->option('force')) {
            $this->error('❌ Estás en PRODUCTION. Ejecuta con --force si estás totalmente seguro.');
            return self::FAILURE;
        }

        // 2. Validar acceso al comando
        if (!$this->validateAccess()) {
            $this->error('❌ Acceso no autorizado.');
            return self::FAILURE;
        }

        // 3. Verificar mysqldump
        if (!$this->testMysqldump()) {
            $this->error('❌ mysqldump no está disponible en el sistema.');
            $this->showMysqldumpHelp();
            return self::FAILURE;
        }

        // 4. Preparar información del backup
        $config = $this->getDatabaseConfig();
        if (!$config) {
            return self::FAILURE;
        }

        $backupInfo = $this->prepareBackupInfo($config);
        
        // 5. Modo prueba
        if ($this->option('test')) {
            return $this->runTestMode($config, $backupInfo);
        }

        // 6. Confirmación (si no es modo silencioso)
        if (!$this->option('silent') && !$this->option('yes')) {
            if (!$this->confirmBackup($config, $backupInfo)) {
                $this->info('✅ Backup cancelado.');
                return self::SUCCESS;
            }
        }

        // 7. Crear backup
        if (!$this->option('silent')) {
            $this->line('🚀 Iniciando backup...');
        }

        if ($this->createDatabaseBackup($config, $backupInfo)) {
            if (!$this->option('silent')) {
                $this->showSuccessMessage($backupInfo);
            } else {
                $this->logBackupSuccess($backupInfo);
            }
            
            // 8. Limpiar backups antiguos
            if ($this->option('keep')) {
                $this->cleanOldBackups($backupInfo['directory'], (int)$this->option('keep'));
            }
            
            return self::SUCCESS;
        }

        return self::FAILURE;
    }

    /**
     * Mostrar encabezado
     */
    private function showHeader(): void
    {
        $this->newLine();
        $this->line('╔═══════════════════════════════════════════════════════════════╗');
        $this->line('║                    SIHO - BACKUP DE SEGURIDAD                 ║');
        $this->line('╚═══════════════════════════════════════════════════════════════╝');
        $this->newLine();
    }

    /**
     * Validar acceso al comando
     */
    private function validateAccess(): bool
    {
        // Si es modo cron/silent con token válido
        if ($this->option('silent')) {
            $cronToken = config('hospital_setup.cron_token', '');
            $scheduleToken = getenv('SCHEDULE_TOKEN');
            
            if ($cronToken && $scheduleToken && hash_equals($cronToken, $scheduleToken)) {
                return true;
            }
            
            // También permitir con password en modo silent
            if ($this->option('password')) {
                return $this->validatePassword();
            }
            
            return false;
        }
        
        // Modo normal: validar contraseña
        return $this->validatePassword();
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
     * Verificar si mysqldump está disponible
     */
    private function testMysqldump(): bool
    {
        $process = new Process(['which', 'mysqldump']);
        $process->run();
        
        if (!$process->isSuccessful()) {
            // Intentar en Windows
            $process = new Process(['where', 'mysqldump.exe']);
            $process->run();
        }
        
        return $process->isSuccessful();
    }

    /**
     * Mostrar ayuda para instalar mysqldump
     */
    private function showMysqldumpHelp(): void
    {
        $this->line('📚 Para instalar mysqldump:');
        $this->line('   Ubuntu/Debian: sudo apt-get install mysql-client');
        $this->line('   CentOS/RHEL: sudo yum install mysql');
        $this->line('   macOS: brew install mysql-client');
        $this->line('   Windows: Instalar MySQL e incluir /bin en el PATH');
    }

    /**
     * Obtener configuración de la base de datos
     */
    private function getDatabaseConfig(): ?array
    {
        $config = config('database.connections.mysql');
        
        if (!$config) {
            $this->error('No se puede leer la configuración de la base de datos.');
            return null;
        }
        
        // Verificar que tenemos los datos necesarios
        $required = ['database', 'username', 'password', 'host'];
        foreach ($required as $key) {
            if (empty($config[$key])) {
                $this->error("Falta configuración de {$key} en la base de datos.");
                return null;
            }
        }
        
        return $config;
    }

    /**
     * Preparar información del backup
     */
    private function prepareBackupInfo(array $config): array
    {
        $timestamp = Carbon::now()->format('Y-m-d_His');
        $dbName = $config['database'];
        
        // Directorio de backups
        $directory = $this->option('path') 
            ? rtrim($this->option('path'), '/')
            : storage_path('backups/siho');
        
        // Crear directorio si no existe
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
            if (!$this->option('silent')) {
                $this->line("📁 Directorio creado: {$directory}");
            }
        }

        // Nombre del archivo
        if ($this->option('filename')) {
            $filename = $this->option('filename');
        } else {
            $filename = "siho_backup_{$dbName}_{$timestamp}";
        }
        
        $filename .= '.sql';
        
        // Comprimir si se solicita
        if ($this->option('compress')) {
            $filename .= '.gz';
        }

        return [
            'directory' => $directory,
            'filename' => $filename,
            'filepath' => "{$directory}/{$filename}",
            'db_name' => $dbName,
            'timestamp' => $timestamp,
        ];
    }

    /**
     * Modo prueba - solo mostrar información
     */
    private function runTestMode(array $config, array $backupInfo): int
    {
        $this->info('🔧 MODO PRUEBA - No se creará ningún archivo');
        
        $sizeInfo = $this->getDatabaseSize($config['database']);
        
        $this->table(['Parámetro', 'Valor'], [
            ['Base de datos', $config['database']],
            ['Host', $config['host']],
            ['Usuario', $config['username']],
            ['Puerto', $config['port'] ?? '3306'],
            ['Tamaño estimado', $sizeInfo],
            ['Archivo', $backupInfo['filename']],
            ['Ubicación', $backupInfo['directory']],
        ]);
        
        // Mostrar comando (sin contraseña)
        $command = $this->buildMysqldumpCommand($config, $backupInfo['filepath']);
        $safeCommand = str_replace($config['password'], '***', $command);
        $this->line("\n🔧 Comando que se ejecutaría:");
        $this->line($safeCommand);
        
        return self::SUCCESS;
    }

    /**
     * Obtener tamaño de la base de datos
     */
    private function getDatabaseSize(string $database): string
    {
        try {
            $result = DB::select("
                SELECT 
                    ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) as size_mb,
                    COUNT(*) as table_count
                FROM information_schema.TABLES 
                WHERE table_schema = ?
            ", [$database]);
            
            if (isset($result[0]->size_mb)) {
                return "{$result[0]->size_mb} MB ({$result[0]->table_count} tablas)";
            }
        } catch (\Exception $e) {
            // Silencioso
        }
        
        return 'No disponible';
    }

    /**
     * Confirmar acción de backup
     */
    private function confirmBackup(array $config, array $backupInfo): bool
    {
        $sizeInfo = $this->getDatabaseSize($config['database']);
        
        $this->warn('📋 RESUMEN DEL BACKUP');
        $this->table(['Parámetro', 'Valor'], [
            ['Base de datos', $config['database']],
            ['Tamaño estimado', $sizeInfo],
            ['Archivo destino', $backupInfo['filename']],
            ['Ubicación', $backupInfo['directory']],
            ['Compresión', $this->option('compress') ? 'Sí (.gz)' : 'No'],
            ['Mantener copias', $this->option('keep') ? $this->option('keep') : 'Todas'],
        ]);
        
        // Mostrar tablas excluidas si las hay
        $excludeTables = $this->option('exclude');
        if (!empty($excludeTables)) {
            $this->line("\n🚫 Tablas EXCLUIDAS del backup:");
            foreach ($excludeTables as $table) {
                $this->line("  • {$table}");
            }
        }
        
        if (app()->environment('production')) {
            $this->newLine();
            $this->error('⚠️  ¡ESTÁS EN PRODUCCIÓN!');
            if (!$this->confirm('¿Continuar con el backup?', false)) {
                return false;
            }
        }
        
        return $this->confirm('¿Crear backup ahora?', true);
    }

    /**
     * Crear el backup de la base de datos
     */
    private function createDatabaseBackup(array $config, array $backupInfo): bool
    {
        if (!$this->option('silent')) {
            $this->line("📊 Creando: {$backupInfo['filename']}");
        }

        $command = $this->buildMysqldumpCommand($config, $backupInfo['filepath']);
        $process = Process::fromShellCommandline($command);
        $process->setTimeout(300); // 5 minutos
        
        try {
            $process->mustRun();
            
            // Verificar que el archivo se creó
            if (!file_exists($backupInfo['filepath'])) {
                $this->error("❌ El archivo no se creó: {$backupInfo['filepath']}");
                return false;
            }
            
            // Verificar tamaño
            $filesize = filesize($backupInfo['filepath']);
            if ($filesize === 0) {
                $this->error("❌ El archivo de backup está vacío: {$backupInfo['filepath']}");
                unlink($backupInfo['filepath']);
                return false;
            }
            
            return true;
            
        } catch (\Exception $e) {
            $this->error('❌ Error al crear backup: ' . $e->getMessage());
            if ($process->getErrorOutput()) {
                $this->error('Detalles: ' . $process->getErrorOutput());
            }
            
            // Eliminar archivo parcial si existe
            if (file_exists($backupInfo['filepath'])) {
                unlink($backupInfo['filepath']);
            }
            
            return false;
        }
    }

 /**
 * Determinar qué tablas respaldar
 */
private function determineTablesToBackup(string $database): array
{
    // Obtener todas las tablas
    $allTables = $this->getAllTables($database);
    
    if (empty($allTables)) {
        return [];
    }
    
    // Si se especifican tablas específicas
    if ($this->option('tables')) {
        $specifiedTables = explode(',', $this->option('tables'));
        $specifiedTables = array_map('trim', $specifiedTables);
        $specifiedTables = array_map('strtolower', $specifiedTables);
        
        return array_filter($allTables, function($table) use ($specifiedTables) {
            return in_array(strtolower($table), $specifiedTables);
        });
    }
    
    // Si se excluyen tablas
    if ($this->option('exclude')) {
        $excludeTables = collect($this->option('exclude'))
            ->map(fn($t) => strtolower(trim($t)))
            ->filter()
            ->values()
            ->all();
        
        if (!empty($excludeTables)) {
            $this->line("🚫 Excluyendo tablas: " . implode(', ', $excludeTables));
            
            return array_filter($allTables, function($table) use ($excludeTables) {
                return !in_array(strtolower($table), $excludeTables);
            });
        }
    }
    
    // Si no hay filtros, devolver todas
    return $allTables;
}
/**
 * Construir comando mysqldump (versión simplificada y robusta)
 */
private function buildMysqldumpCommand(array $config, string $outputFile): string
{
    $parts = ['mysqldump'];
    
    // Credenciales
    $parts[] = "--host={$config['host']}";
    $parts[] = "--user={$config['username']}";
    $parts[] = "--password={$config['password']}";
    
    if (isset($config['port']) && $config['port'] && $config['port'] != 3306) {
        $parts[] = "--port={$config['port']}";
    }
    
    // Opciones recomendadas para MySQL
    $parts[] = '--single-transaction';
    $parts[] = '--routines';
    $parts[] = '--triggers';
    $parts[] = '--events';
    
    // Solo para modo desarrollo/testing
    if (app()->environment(['local', 'testing'])) {
        $parts[] = '--extended-insert';
        $parts[] = '--complete-insert';
    }
    
    // Opciones según parámetros
    if ($this->option('no-data')) {
        $parts[] = '--no-data';
    }
    
    if ($this->option('only-data')) {
        $parts[] = '--no-create-info';
    }
    
    // Determinar qué tablas respaldar
    $tablesToBackup = $this->determineTablesToBackup($config['database']);
    
    if (empty($tablesToBackup)) {
        throw new \Exception('No hay tablas para respaldar.');
    }
    
    // Agregar base de datos y tablas
    $parts[] = $config['database'];
    foreach ($tablesToBackup as $table) {
        $parts[] = escapeshellarg($table);
    }
    
    // Comprimir si es necesario
    if ($this->option('compress')) {
        $parts[] = '| gzip';
    }
    
    $parts[] = '>';
    $parts[] = escapeshellarg($outputFile);
    
    $command = implode(' ', $parts);
    
    // Para debug (opcional)
    if (!$this->option('silent') && app()->environment('local')) {
        $safeCommand = str_replace($config['password'], '***', $command);
        $this->line("🔧 Comando: " . substr($safeCommand, 0, 200) . '...');
    }
    
    return $command;
}

    /**
 * Obtener todas las tablas de la base de datos
 */
private function getAllTables(string $database): array
{
    try {
        // Método 1: Usar SHOW TABLES (más compatible)
        $results = DB::select('SHOW TABLES');
        
        if (empty($results)) {
            // Método 2: Intentar con información del schema
            try {
                $results = DB::select("
                    SELECT TABLE_NAME as table_name
                    FROM INFORMATION_SCHEMA.TABLES 
                    WHERE TABLE_SCHEMA = ?
                    AND TABLE_TYPE = 'BASE TABLE'
                ", [$database]);
            } catch (\Exception $e) {
                // Método 3: Usar el nombre de la base de datos dinámicamente
                $dbName = DB::getDatabaseName();
                $results = DB::select("SHOW TABLES FROM `{$dbName}`");
            }
        }
        
        // Procesar resultados
        $tables = [];
        foreach ($results as $row) {
            // Convertir objeto a array
            $rowArray = (array)$row;
            // El primer valor es el nombre de la tabla
            $tableName = reset($rowArray);
            if ($tableName) {
                $tables[] = $tableName;
            }
        }
        
        if (empty($tables)) {
            $this->warn("⚠️  No se pudieron obtener las tablas usando métodos estándar.");
            $this->line("Intentando método alternativo...");
            
            // Método alternativo: Consulta directa
            $pdo = DB::connection()->getPdo();
            $stmt = $pdo->query("SHOW TABLES");
            $tables = $stmt->fetchAll(\PDO::FETCH_COLUMN);
        }
        
        if (empty($tables)) {
            throw new \Exception("No se encontraron tablas en la base de datos '{$database}'. Verifica que la base de datos exista y tenga tablas.");
        }
        
        // Ordenar alfabéticamente
        sort($tables);
        
        if (!$this->option('silent')) {
            $this->line("✅ Encontradas " . count($tables) . " tablas: " . implode(', ', array_slice($tables, 0, 5)) . (count($tables) > 5 ? '...' : ''));
        }
        
        return $tables;
        
    } catch (\Exception $e) {
        $this->error("❌ Error obteniendo tablas: " . $e->getMessage());
        
        // Último intento: usar una lista hardcodeada si sabemos las tablas
        $knownTables = [
            'cache', 'cache_locks', 'categorias', 'consulta_doctor', 
            'empleados', 'encargados', 'examenes', 'examenes_medicos',
            'expedientes', 'expediente_examen', 'failed_jobs', 'jobs',
            'job_batches', 'licencias', 'migrations', 'pacientes',
            'password_reset_tokens', 'personas', 'recetas', 
            'relacion_paciente_encargado', 'sessions', 'signos_vitales',
            'users'
        ];
        
        $this->warn("⚠️  Usando lista de tablas conocidas (" . count($knownTables) . " tablas)");
        return $knownTables;
    }
}

    /**
     * Loggear éxito del backup (para modo silent/cron)
     */
    private function logBackupSuccess(array $backupInfo): void
    {
        $filesize = filesize($backupInfo['filepath']);
        $sizeFormatted = $this->formatBytes($filesize);
        
        Log::info('Backup SIHO creado exitosamente', [
            'file' => $backupInfo['filename'],
            'size' => $sizeFormatted,
            'path' => $backupInfo['directory'],
            'database' => $backupInfo['db_name'],
            'timestamp' => Carbon::now()->toDateTimeString(),
        ]);
    }

    /**
     * Limpiar backups antiguos
     */
    private function cleanOldBackups(string $directory, int $keep): void
    {
        if (!$this->option('silent')) {
            $this->line("🧹 Limpiando backups antiguos (manteniendo {$keep})...");
        }
        
        $files = glob("{$directory}/*.sql") + glob("{$directory}/*.sql.gz");
        
        // Ordenar por fecha (más reciente primero)
        usort($files, function($a, $b) {
            return filemtime($b) - filemtime($a);
        });
        
        $toDelete = array_slice($files, $keep);
        
        $deleted = 0;
        foreach ($toDelete as $file) {
            if (unlink($file)) {
                $deleted++;
                if (!$this->option('silent')) {
                    $this->line("  🗑️  Eliminado: " . basename($file));
                }
            }
        }
        
        if ($deleted > 0 && !$this->option('silent')) {
            $this->info("✅ Limpieza completada: {$deleted} archivos eliminados.");
        }
    }

    /**
     * Mostrar mensaje de éxito
     */
    private function showSuccessMessage(array $backupInfo): void
    {
        $filesize = filesize($backupInfo['filepath']);
        $sizeFormatted = $this->formatBytes($filesize);
        
        $this->newLine();
        $this->info('✅ BACKUP COMPLETADO EXITOSAMENTE');
        $this->newLine();
        
        $this->table(['Información', 'Valor'], [
            ['📁 Archivo', $backupInfo['filename']],
            ['📂 Ubicación', $backupInfo['directory']],
            ['💾 Tamaño', $sizeFormatted],
            ['🕐 Fecha', Carbon::now()->format('Y-m-d H:i:s')],
            ['🗃️ Base de datos', $backupInfo['db_name']],
        ]);
        
        $this->newLine();
        $this->line('📋 Comando para restaurar:');
        $this->line("   php artisan hospital:restore --password=SIHOSys77@ {$backupInfo['filepath']}");
        
        // Mostrar backups existentes
        $this->showExistingBackups($backupInfo['directory']);
    }

    /**
     * Mostrar backups existentes
     */
    private function showExistingBackups(string $directory): void
    {
        $files = glob("{$directory}/*.sql") + glob("{$directory}/*.sql.gz");
        
        if (count($files) > 0) {
            $this->line('📚 Backups disponibles en el directorio:');
            
            $backupList = [];
            foreach ($files as $file) {
                $backupList[] = [
                    'Archivo' => basename($file),
                    'Tamaño' => $this->formatBytes(filesize($file)),
                    'Modificado' => date('Y-m-d H:i', filemtime($file)),
                ];
            }
            
            // Ordenar por fecha (más reciente primero)
            usort($backupList, function($a, $b) {
                return strtotime($b['Modificado']) - strtotime($a['Modificado']);
            });
            
            // Mostrar solo los últimos 5
            $this->table(['Archivo', 'Tamaño', 'Modificado'], array_slice($backupList, 0, 5));
            
            if (count($files) > 5) {
                $this->line("... y " . (count($files) - 5) . " backups más.");
            }
        }
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