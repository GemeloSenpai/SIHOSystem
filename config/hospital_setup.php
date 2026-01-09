<?php

return [
    // Hash bcrypt del password maestro para poder ejecutar los comandos
    'install_hash' => env('HOSPITAL_INSTALL_HASH', null),
    
    // Token seguro para ejecución automática desde cron
    'cron_token' => env('HOSPITAL_CRON_TOKEN', null),

    // Tablas que NO se truncan en el comando hospital:install
    'preserve_tables' => [
        'categorias',
        'examenes',
    ],

    // Datos del admin por defecto (se hashea en runtime con bcrypt)
    'default_admin' => [
        'name'     => env('HOSPITAL_DEFAULT_ADMIN_NAME', 'Administrador General'),
        'email'    => env('HOSPITAL_DEFAULT_ADMIN_EMAIL', 'admin@hospital.local'),
        'password' => env('HOSPITAL_DEFAULT_ADMIN_PASSWORD', 'Admin@12345'),
    ],
    
    // Configuración de backups
    'backup' => [
        'default_path' => storage_path('backups/siho'),
        'keep_daily' => 7,
        'keep_weekly' => 4,
        'compress' => true,
    ],
];
