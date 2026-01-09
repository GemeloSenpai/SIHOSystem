<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\MedicoController;
use App\Http\Controllers\EnfermeriaController;
use App\Http\Controllers\RecepcionController;
use App\Http\Controllers\ExamenController;
use App\Http\Controllers\ExpedienteController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ExportarExamen;
use App\Http\Controllers\PdfExpedienteController;
use Illuminate\Support\Facades\Auth; 
use App\Http\Controllers\LicenciaController;
use App\Http\Controllers\RecetaController;
use Illuminate\Http\Request;
use App\Models\Examen;
use App\Http\Controllers\ExportacionExpedienteController;
use App\Http\Controllers\ExamenesConsultaController;
use App\Http\Controllers\EstadisticaAdminController;
use App\Http\Controllers\LaboratorioController;

Route::get('/', function () {
    return view('auth.login');
});

// Redireccionando a los usuarios
Route::get('/', function () {
    if (Auth::check()) {
        return match (Auth::user()->role) {
            'admin'         => redirect()->route('dashboard.admin'),
            'medico'        => redirect()->route('dashboard.medico'),
            'enfermero'     => redirect()->route('dashboard.recepcion'),
            'recepcionista' => redirect()->route('dashboard.recepcion'),
            default         => redirect()->route('login'),
        };
    }
    return view('auth.login');
})->name('home');

// ====================================================================================================
// Rutas de politicas y terminos de condiciones de uso
// ====================================================================================================
Route::get('/licencia', [LicenciaController::class, 'form'])->name('licencia.form');
Route::post('/licencia/activar', [LicenciaController::class, 'activar'])->name('licencia.activar');
Route::view('/legal/terminos', 'legal.terminos')->name('legal.terminos');
Route::view('/legal/aspectos', 'legal.aspectos')->name('legal.aspectos');

// ====================================================================================================
// Rutas de breeze profile
// ====================================================================================================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])
    ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
    ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
    ->name('profile.destroy');
});

// ====================================================================================================
// Rutas compartidas por Admin y Medico
// ====================================================================================================
Route::middleware(['auth', 'active', 'role:admin,medico'])->group(function () {
    
    // ------------------------------------------------------------------------------------------------
    // Rutas de Expedientes
    // ------------------------------------------------------------------------------------------------
    // Vista para gestionar expedientes 
    Route::get('/expedientes', [ExpedienteController::class, 'index'])
    ->name('expedientes.index');

    // Vista para ver un expediente detallado
    Route::get('/expedientes/ver/{id}', [ExpedienteController::class, 'ver'])
    ->name('expedientes.ver');

    // Vista para ver el expediente en formato profesional, listo para imprimir
    Route::get('/expedientes/completo/{id}', [ExpedienteController::class, 'expedienteCompleto'])
    ->name('expedientes.completo');  
    
    // MOVER AQUÍ (estaban en el grupo solo admin)
    Route::get('/expedientes/{id}/editar', [ExpedienteController::class, 'edit'])
    ->name('expedientes.editar');

    Route::put('/expedientes/{id}', [ExpedienteController::class, 'update'])
    ->name('expedientes.update');

    // NUEVO: vista de edición independiente
    Route::get('/expedientes/editar/{id}', [ExpedienteController::class, 'edit'])
    ->name('expedientes.editar');

    Route::get('/admin/expedientes/{id}/ver-examenes', [ExportarExamen::class, 'show'])
    ->name('admin.ver-examenes');

    Route::get('admin/expedientes/{id}/read-examenes', [ExportarExamen::class, 'vistaExamenes'])
    ->name('admin.vista-ver-examenes');

    Route::get('/expedientes/completo/{id}/pdf', [\App\Http\Controllers\PdfExpedienteController::class, 'downloadViaChrome'])
    ->name('expedientes.completo.pdf');

    Route::get('/expedientes/completo/{expediente}/examenes/imprimir',
        [\App\Http\Controllers\ImprimirExamenesController::class, 'show']
    )->name('expedientes.examenes.imprimir');

    Route::get('/expedientes/leer/{id}', [\App\Http\Controllers\ExpedienteController::class, 'read'])
    ->name('expedientes.leer');

    // routes/web.php  (dentro del grupo admin,medico)
    Route::post('/expedientes/{expediente}/examenes',
        [\App\Http\Controllers\ExamenesConsultaController::class, 'store']
    )->name('expedientes.examenes.store');

    Route::delete('/expedientes/{expediente}/examenes/{examenMedico}',
        [\App\Http\Controllers\ExamenesConsultaController::class, 'destroy']
    )->name('expedientes.examenes.destroy');


    // ------------------------------------------------------------------------------------------------
    // Rutas para Exámenes de Consulta (AJAX - Compartidas)
    // ------------------------------------------------------------------------------------------------
    Route::prefix('expedientes/{expediente}')->group(function () {
        
        // CRUD de exámenes para consulta (con AJAX)
        Route::post('/examenes', [ExamenesConsultaController::class, 'store'])
        ->name('expedientes.examenes.store');

        Route::delete('/examenes/{examenMedico}', [ExamenesConsultaController::class, 'destroy'])
        ->name('expedientes.examenes.destroy');

        Route::get('/examenes/lista', [ExamenesConsultaController::class, 'lista'])
        ->name('expedientes.examenes.lista'); // NUEVA: para AJAX
    });


    // ============================================
    // RUTAS PARA RECETAS MÉDICAS (ADMIN - CRUD COMPLETO)
    // ============================================
    
    // Crear receta desde expediente (Admin)
    Route::get('/admin/recetas/crear/{expediente}', [RecetaController::class, 'crear'])
        ->name('admin.recetas.crear');
    
    // Guardar nueva receta (Admin)
    Route::post('/admin/recetas/{expediente}', [RecetaController::class, 'store'])
        ->name('admin.recetas.store');
    
    // Ver receta (detalle) - admin
    Route::get('/admin/recetas/{receta}', [RecetaController::class, 'show'])
        ->name('admin.recetas.ver');
    
    // Editar receta (formulario) - admin
    Route::get('/admin/recetas/{receta}/editar', [RecetaController::class, 'editar'])
        ->name('admin.recetas.editar');
    
    // Actualizar receta - admin
    Route::put('/admin/recetas/{receta}', [RecetaController::class, 'update'])
        ->name('admin.recetas.update');
    
    // Eliminar receta - admin
    Route::delete('/admin/recetas/{receta}', [RecetaController::class, 'destroy'])
        ->name('admin.recetas.destroy');
    
    // Imprimir receta - admin
    Route::get('/admin/recetas/{receta}/imprimir', [RecetaController::class, 'imprimir'])
        ->name('admin.recetas.imprimir');
    
    // API: Buscar recetas por paciente (JSON) - admin
    Route::get('/admin/recetas/buscar-por-paciente', [RecetaController::class, 'buscarPorPaciente'])
        ->name('admin.recetas.buscar-por-paciente');
    
    // API: Obtener receta por expediente (JSON) - admin
    Route::get('/admin/recetas/por-expediente/{expediente}', [RecetaController::class, 'porExpediente'])
        ->name('admin.recetas.por-expediente');

    // ============================================
        // RUTAS PARA RECETAS MÉDICAS (MÉDICO - CRUD COMPLETO)
        // ============================================

        // Crear receta desde expediente (Médico)
        Route::get('/medico/recetas/crear/{expediente}', [RecetaController::class, 'crear'])
            ->name('medico.recetas.crear');

        // Guardar nueva receta (Médico)
        Route::post('/medico/recetas/{expediente}', [RecetaController::class, 'store'])
            ->name('medico.recetas.store');

        // Ver receta (detalle) - médico
        Route::get('/medico/recetas/{receta}', [RecetaController::class, 'show'])
            ->name('medico.recetas.ver');

        // Editar receta (formulario) - médico
        Route::get('/medico/recetas/{receta}/editar', [RecetaController::class, 'editar'])
            ->name('medico.recetas.editar');

        // Actualizar receta - médico
        Route::put('/medico/recetas/{receta}', [RecetaController::class, 'update'])
            ->name('medico.recetas.update');

        // Eliminar receta - médico
        Route::delete('/medico/recetas/{receta}', [RecetaController::class, 'destroy'])
            ->name('medico.recetas.destroy');

        // Imprimir receta - médico
        Route::get('/medico/recetas/{receta}/imprimir', [RecetaController::class, 'imprimir'])
            ->name('medico.recetas.imprimir');

    // Ruta para ver la boleta de exámenes
    Route::get('/examenes/ver/{id}', [ExamenController::class, 'ver'])->name('examenes.ver');

});

// ====================================================================================================
// Rutas para el laboratorio
// ====================================================================================================
Route::middleware(['auth', 'active', 'role:laboratorio'])->group(function () {

    // Dashboard de laboratorio
    Route::view('/laboratorio', 'dashboards.LabDashboard')
    ->name('dashboard.laboratorio');

    // Vista de gestión de expedientes (específica para laboratorio)
    Route::get('/laboratorio/expedientes', [LaboratorioController::class, 'gestionarExpedientes'])
    ->name('laboratorio.expedientes.index');
    
    // Ruta para ver exámenes de un expediente
    Route::get('/laboratorio/expedientes/{id}/examenes', [LaboratorioController::class, 'verExamenesExpediente'])
    ->name('laboratorio.expedientes.examenes');
});

Route::middleware(['auth', 'active', 'role:admin,medico,laboratorio'])->group(function () {
    
    Route::get('/expedientes', [ExpedienteController::class, 'index'])
    ->name('expedientes.index');

    // Vista para gestionar expedientes 
    Route::get('/expedientes', [ExpedienteController::class, 'index'])
    ->name('expedientes.index');

    // Vista para ver un expediente detallado
    Route::get('/expedientes/ver/{id}', [ExpedienteController::class, 'ver'])
    ->name('expedientes.ver');

    // Vista para ver el expediente en formato profesional, listo para imprimir
    Route::get('/expedientes/completo/{id}', [ExpedienteController::class, 'expedienteCompleto'])
    ->name('expedientes.completo');  
    
    // Ruta para ver exámenes (para laboratorio específicamente)
    Route::get('/expedientes/{id}/examenes', [LaboratorioController::class, 'verExamenesExpediente'])
    ->name('expedientes.examenes.laboratorio');

    // Ruta para leer expediente
    Route::get('/expedientes/leer/{id}', [ExpedienteController::class, 'read'])
    ->name('expedientes.leer');

    // Ruta para ver la boleta de exámenes
    Route::get('/examenes/ver/{id}', [ExamenController::class, 'ver'])->name('examenes.ver');
    
    // Ruta para ver exámenes específicos
    Route::get('admin/expedientes/{id}/read-examenes', [ExportarExamen::class, 'vistaExamenes'])
    ->name('admin.vista-ver-examenes');

    Route::get('/expedientes/completo/{expediente}/examenes/imprimir',
        [\App\Http\Controllers\ImprimirExamenesController::class, 'show']
    )->name('expedientes.examenes.imprimir');
});

Route::get('/admin/gestionar-examenes', function () {
    $categorias = \App\Models\Categoria::with('examenes')->get();
    return view('logica.admin.gestionar-examenes', compact('categorias'));
})->middleware(['auth', 'role:admin,medico'])->name('admin.gestionar.examenes');

// ====================================================================================================
// Rutas del Administrador del sistema
// ====================================================================================================
Route::middleware(['auth', 'active', 'role:admin'])->group(function () {

    // Dashboard principal del admin (panel de control)
    Route::get('/admin', [AdminDashboardController::class, 'index'])
    ->name('dashboard.admin');

    // Form de alta de usuario (solo vista)
    Route::get('/admin/registrar-usuario', function () {
        return view('logica.admin.registrar-usuario');
    })->name('admin.registrar.usuario');

    // Crear usuario (procesa el form de registro)
    Route::post('/admin/users/store', [UserController::class, 'store'])
    ->name('admin.users.store');

    // Listado/gestión de expedientes (vista principal para admin)
    Route::get('/admin/expedientes', [ExpedienteController::class, 'index'])
    ->name('admin.expedientes.index');

    // Forzar cierre de una sesión concreta por session-id
    Route::delete('/admin/sesiones/{id}', [AdminDashboardController::class, 'cerrarSesion'])
    ->name('admin.sesiones.forzarLogout');

    // Cerrar todas las sesiones activas de un usuario (por userId)
    Route::delete('/admin/sesiones-usuario/{userId}', [AdminDashboardController::class, 'cerrarTodas'])
    ->name('admin.sesiones.cerrarTodas');

    // Cierre masivo/acciones de sesiones (endpoint genérico)
    Route::post('/cerrar-sesiones', [AdminDashboardController::class, 'cerrarSesiones'])
    ->name('admin.cerrar.sesiones');

    // Exportación de expedientes (ej. CSV/Excel)
    Route::get('/exportar-expedientes', [\App\Http\Controllers\ExportacionExpedienteController::class, 'exportar'])
    ->name('exportar.expedientes');

    // ===== Gestión de usuarios (CRUD + acciones rápidas) =====
    // Listado/tabla de usuarios
    Route::get('/admin/gestionar-usuarios', [\App\Http\Controllers\UsuarioController::class, 'index'])
    ->name('admin.usuarios.index');

    // Búsqueda (querystring) - IMPORTANTE: definida antes que /admin/usuarios/{id}
    Route::get('/admin/usuarios/buscar', [\App\Http\Controllers\UsuarioController::class, 'search'])
    ->name('admin.usuarios.search');

    // Ver detalle de un usuario por ID
    Route::get('/admin/usuarios/{id}', [\App\Http\Controllers\UsuarioController::class, 'show'])
    ->name('admin.usuarios.show');

    // Form de edición de un usuario
    Route::get('/admin/usuarios/{id}/edit', [\App\Http\Controllers\UsuarioController::class, 'edit'])
    ->name('admin.usuarios.edit');

    // Actualización estándar de usuario (PUT)
    Route::put('/admin/usuarios/{id}', [\App\Http\Controllers\UsuarioController::class, 'update'])
    ->name('admin.usuarios.update');

    // Actualización rápida/inline (PATCH) para cambios puntuales
    Route::patch('/admin/usuarios/{id}/quick', [\App\Http\Controllers\UsuarioController::class, 'quickUpdate'])
    ->name('admin.usuarios.quick');

    // Activar/desactivar usuario (toggle de estado)
    Route::post('/admin/usuarios/{id}/toggle-estado', [\App\Http\Controllers\UsuarioController::class, 'toggleEstado'])
    ->name('admin.usuarios.toggle');

    // Reset de contraseña
    Route::post('/admin/usuarios/{id}/reset-password', [\App\Http\Controllers\UsuarioController::class, 'resetPassword'])
    ->name('admin.usuarios.reset');

    // Eliminación de usuario
    Route::delete('/admin/usuarios/{id}', [\App\Http\Controllers\UsuarioController::class, 'destroy'])
    ->name('admin.usuarios.destroy');

    // =========================
    //   EXÁMENES 
    // =========================
    // Vista principal (listar/gestionar)
    Route::get('/admin/examenes', [ExamenController::class, 'index'])
    ->name('admin.gestionar.examenes');

    // Crear examen
    Route::post('/admin/examenes', [ExamenController::class, 'store'])
    ->name('admin.examenes.store');

    // Editar examen
    Route::put('/admin/examenes/{id}', [ExamenController::class, 'update'])
    ->name('admin.examenes.update');

    // Eliminar examen (el controlador bloquea si está en uso)
    Route::delete('/admin/examenes/{id}', [ExamenController::class, 'destroy'])
    ->name('admin.examenes.destroy');

    // Nueva ruta para crear categoría
    Route::post('/admin/categorias', [ExamenController::class, 'storeCategoria'])->name('admin.categorias.store');
        
    // Seccion de estadisticas 
    Route::get('/admin/exportar-estadisticas', [AdminDashboardController::class, 'exportarEstadisticas'])
    ->name('admin.exportar.estadisticas');
    
    // Cerrar todas las sesiones
    Route::post('/admin/cerrar-sesiones', [AdminDashboardController::class, 'cerrarSesiones'])
    ->name('admin.cerrar.sesiones');
    
    // Cerrar sesiones de un usuario específico
    Route::delete('/admin/sesiones-usuario/{id}', [AdminDashboardController::class, 'cerrarTodas'])
    ->name('admin.cerrar.sesiones.usuario');  
});

// ====================================================================================================
// Rutas para el rol de recepcion
// ====================================================================================================
Route::middleware(['auth', 'active', 'role:recepcionista'])->group(function () {

    Route::view('/recepcion', 'dashboards.RecepcionDashboard')->name('dashboard.recepcion');

    // Mostrar el formulario registrar-paciente.blade.php
    Route::get('/recepcion/registrar-paciente', function () {
        return view('logica.recepcion.registrar-paciente');
    })->name('recepcion.pacientes.form');

    // Guardar el paciente
    Route::post('/recepcion/pacientes/store', [RecepcionController::class, 'storePaciente'])
    ->name('recepcion.pacientes.store');

    // Ruta principal (listado)
    Route::get('/gestionar-pacientes', [PacienteController::class, 'index'])
    ->name('pacientes.index');
    
    // Ruta para búsqueda (POST)
    Route::post('/gestionar-pacientes/buscar', [PacienteController::class, 'buscar'])
    ->name('pacientes.buscar');
    
    // Ruta principal
    Route::get('/gestionar-pacientes', [PacienteController::class, 'index'])
    ->name('pacientes.index');
    
    // Búsqueda
    Route::post('/gestionar-pacientes/buscar', [PacienteController::class, 'buscar'])
    ->name('pacientes.buscar');
    
    // Operaciones CRUD
    Route::prefix('recepcion')->group(function () {
        Route::get('/ver-pacientes', [PacienteController::class, 'verPacientes'])
        ->name('recepcion.verPacientes');
            
        Route::get('/pacientes/{id}/detalles', [PacienteController::class, 'detallesPaciente'])
        ->name('recepcion.paciente.detalles');
            
        Route::delete('/pacientes/{id}', [PacienteController::class, 'eliminarPaciente'])
        ->name('recepcion.paciente.eliminar');

        Route::get('recepcion/pacientes/{id}/agregar-encargado', [PacienteController::class, 'agregarEncargado'])
        ->name('recepcion.pacientes.agregarEncargado');
    });

    // Ruta para vista de gestión de pacientes
    Route::get('/recepcion/pacientes/gestion', [RecepcionController::class, 'verPacientes'])
    ->name('recepcion.pacientes.gestion');

    // Ruta para mostrar el formulario de asignación de encargado
    Route::get('/recepcion/pacientes/{id}/agregar-encargado', [RecepcionController::class, 'agregarEncargado'])
    ->name('recepcion.pacientes.agregarEncargado');

    // Ruta para guardar la relación entre paciente y encargado
    Route::prefix('recepcion')->name('recepcion.')->group(function () {
        // Mostrar formulario para agregar encargado a un paciente
        Route::get('pacientes/{id}/agregar-encargado', [RecepcionController::class, 'agregarEncargado'])
        ->name('pacientes.agregarEncargado');

        // Buscar encargados por DNI o nombre (GET con query)
        Route::get('pacientes/buscar-encargados', [RecepcionController::class, 'buscarEncargados'])
        ->name('pacientes.buscarEncargados');

        // Guardar la relación paciente - encargado (POST)
        Route::post('pacientes/guardar-relacion', [RecepcionController::class, 'guardarRelacionEncargado'])
        ->name('pacientes.guardarRelacion');
    });

    Route::post('/recepcion/pacientes/seleccionar-encargado', [RecepcionController::class, 'seleccionarEncargado'])
    ->name('recepcion.pacientes.seleccionarEncargado');

    Route::get('/recepcion/pacientes/{id_paciente}/asignar-encargado', [RecepcionController::class, 'formAgregarEncargado'])
    ->name('recepcion.pacientes.formAgregarEncargado');

    Route::post('/recepcion/pacientes/guardar-relacion', [RecepcionController::class, 'guardarRelacion'])
    ->name('recepcion.pacientes.guardarRelacion');

    Route::post('/recepcion/pacientes/crear-encargado-relacion', [RecepcionController::class, 'crearEncargadoYRelacion'])
    ->name('recepcion.pacientes.crearEncargadoYRelacion');

    // Editar paciente
    Route::get('/recepcion/pacientes/{id}/editar', [RecepcionController::class, 'editarPacienteForm'])
    ->name('recepcion.pacientes.editar');

    Route::put('/recepcion/pacientes/{id}', [RecepcionController::class, 'actualizarPaciente'])
    ->name('recepcion.pacientes.actualizar');

    // Actualización completa de un encargado (todas las columnas de la persona)
    Route::get('/recepcion/ver-pacientes', [PacienteController::class, 'verPacientes'])
    ->name('recepcion.verPacientes');

    // Vista BUSCAR (solo encargados en tu caso actual)
    Route::get('/recepcion/buscar', [RecepcionController::class, 'vistaBuscar'])
    ->name('recepcion.buscar');

    // ===== APIs Encargados =====

    // Listar/buscar encargados (q, page, per_page) — la tabla llama a esta
    Route::get('/recepcion/api/encargados', [RecepcionController::class, 'apiEncargados'])
    ->name('recepcion.api.encargados');

    // Edición rápida (teléfono/dirección) — opcional si lo usas en otras vistas
    Route::patch('/recepcion/api/encargados/{id}', [RecepcionController::class, 'apiEncargadoQuickUpdate'])
    ->name('recepcion.api.encargados.quick');

    // **Actualización COMPLETA** para el modal (nombre, apellido, dni, edad, sexo, teléfono, dirección)
    Route::patch('/recepcion/api/encargados/{id}/full', [RecepcionController::class, 'apiEncargadoUpdateFull'])
    ->name('recepcion.api.encargados.update');

    Route::get('/recepcion', [\App\Http\Controllers\RecepDashboardController::class, 'index'])
    ->name('dashboard.recepcion');
});

// ====================================================================================================
// Rutas para Enfermero
// ====================================================================================================
Route::middleware(['auth', 'active', 'role:enfermero'])->group(function () {
    
    // Dashboard de enfermero
    Route::view('/enfermeria', 'dashboards.EnfermeroDashboard')
        ->name('dashboard.enfermero');
});

// ====================================================================================================
// Grupo COMPARTIDO para recepcionista + enfermero ===
// ====================================================================================================
Route::middleware(['auth', 'active', 'role:recepcionista,enfermero'])->group(function () {

    Route::view('/recepcion', 'dashboards.RecepcionDashboard')->name('dashboard.recepcion');

    // ----- RUTAS DE RECEPCIÓN (sin el dashboard) -----
    Route::get('/recepcion/registrar-paciente', fn() => view('logica.recepcion.registrar-paciente'))
        ->name('recepcion.pacientes.form');

    Route::post('/recepcion/pacientes/store', [RecepcionController::class, 'storePaciente'])
        ->name('recepcion.pacientes.store');

    // Pacientes (listado/búsqueda CRUD)
    Route::get('/gestionar-pacientes', [PacienteController::class, 'index'])->name('pacientes.index');
    Route::post('/gestionar-pacientes/buscar', [PacienteController::class, 'buscar'])->name('pacientes.buscar');

    Route::prefix('recepcion')->group(function () {
        Route::get('/ver-pacientes', [PacienteController::class, 'verPacientes'])->name('recepcion.verPacientes');
        Route::get('/pacientes/{id}/detalles', [PacienteController::class, 'detallesPaciente'])->name('recepcion.paciente.detalles');
        Route::delete('/pacientes/{id}', [PacienteController::class, 'eliminarPaciente'])->name('recepcion.paciente.eliminar');

        // Encargados
        Route::get('pacientes/{id}/agregar-encargado', [RecepcionController::class, 'agregarEncargado'])->name('pacientes.agregarEncargado');
        Route::get('pacientes/buscar-encargados', [RecepcionController::class, 'buscarEncargados'])->name('pacientes.buscarEncargados');
        Route::post('pacientes/guardar-relacion', [RecepcionController::class, 'guardarRelacionEncargado'])->name('pacientes.guardarRelacion');

        Route::patch('/api/encargados/{id}', [RecepcionController::class, 'apiEncargadoQuickUpdate'])->name('api.encargados.quick');
        Route::patch('/api/encargados/{id}/full', [RecepcionController::class, 'apiEncargadoUpdateFull'])->name('api.encargados.update');
        Route::get('/api/encargados', [RecepcionController::class, 'apiEncargados'])->name('api.encargados');
    });

    Route::post('/recepcion/pacientes/seleccionar-encargado', [RecepcionController::class, 'seleccionarEncargado'])
        ->name('recepcion.pacientes.seleccionarEncargado');
    Route::get('/recepcion/pacientes/{id_paciente}/asignar-encargado', [RecepcionController::class, 'formAgregarEncargado'])
        ->name('recepcion.pacientes.formAgregarEncargado');
    Route::post('/recepcion/pacientes/guardar-relacion', [RecepcionController::class, 'guardarRelacion'])
        ->name('recepcion.pacientes.guardarRelacion');
    Route::post('/recepcion/pacientes/crear-encargado-relacion', [RecepcionController::class, 'crearEncargadoYRelacion'])
        ->name('recepcion.pacientes.crearEncargadoYRelacion');

    Route::get('/recepcion/buscar', [RecepcionController::class, 'vistaBuscar'])->name('recepcion.buscar');

    Route::get('/recepcion/pacientes/gestion', [RecepcionController::class, 'verPacientes'])
        ->name('recepcion.pacientes.gestion');

    // Edición de paciente
    Route::get('/recepcion/pacientes/{id}/editar', [RecepcionController::class, 'editarPacienteForm'])->name('recepcion.pacientes.editar');
    Route::put('/recepcion/pacientes/{id}', [RecepcionController::class, 'actualizarPaciente'])->name('recepcion.pacientes.actualizar');

    // ----- RUTAS DE ENFERMERÍA (sin el dashboard) -----
    Route::get('/enfermeria/registrar-signos-vitales', [EnfermeriaController::class, 'formRegistrarSignos'])
        ->name('enfermero.signosvitales.form');

    Route::post('/enfermeria/buscar-paciente', [EnfermeriaController::class, 'buscarPacienteForm'])
        ->name('enfermeria.paciente.buscar');

    Route::post('/enfermeria/guardar-signos', [EnfermeriaController::class, 'guardarSignos'])
        ->name('enfermeria.signos.store');

    Route::get('/enfermeria/signos/paciente/{paciente}', [EnfermeriaController::class, 'historial'])
        ->name('enfermeria.signos.historial');

    Route::put('/enfermeria/signos/{signo}', [EnfermeriaController::class, 'actualizar'])
        ->name('enfermeria.signos.update');

    // ===== APIs Encargados virnr de recepcion =====
    Route::get('/recepcion/api/encargados', [RecepcionController::class, 'apiEncargados'])
        ->name('recepcion.api.encargados');

    Route::patch('/recepcion/api/encargados/{id}', [RecepcionController::class, 'apiEncargadoQuickUpdate'])
        ->name('recepcion.api.encargados.quick');

    Route::patch('/recepcion/api/encargados/{id}/full', [RecepcionController::class, 'apiEncargadoUpdateFull'])
        ->name('recepcion.api.encargados.update');

    Route::get('/recepcion', [\App\Http\Controllers\RecepDashboardController::class, 'index'])
        ->name('dashboard.recepcion');
        
    Route::get('/recepcion/registrar-paciente', function () {
        return view('logica.recepcion.registrar-paciente');
    })->name('recepcion.pacientes.form');

    Route::post('/recepcion/pacientes/store', [RecepcionController::class, 'storePaciente'])
    ->name('recepcion.pacientes.store');

    Route::get('/gestionar-pacientes', [PacienteController::class, 'index'])
    ->name('pacientes.index');
    
    Route::post('/gestionar-pacientes/buscar', [PacienteController::class, 'buscar'])
    ->name('pacientes.buscar');
    
    Route::get('/gestionar-pacientes', [PacienteController::class, 'index'])
    ->name('pacientes.index');
    
    Route::post('/gestionar-pacientes/buscar', [PacienteController::class, 'buscar'])
    ->name('pacientes.buscar');
    
    Route::prefix('recepcion')->group(function () {
        Route::get('/ver-pacientes', [PacienteController::class, 'verPacientes'])
        ->name('recepcion.verPacientes');
            
        Route::get('/pacientes/{id}/detalles', [PacienteController::class, 'detallesPaciente'])
        ->name('recepcion.paciente.detalles');
            
        Route::delete('/pacientes/{id}', [PacienteController::class, 'eliminarPaciente'])
        ->name('recepcion.paciente.eliminar');

        Route::get('recepcion/pacientes/{id}/agregar-encargado', [PacienteController::class, 'agregarEncargado'])
        ->name('recepcion.pacientes.agregarEncargado');
    });

    Route::get('/recepcion/pacientes/gestion', [RecepcionController::class, 'verPacientes'])
    ->name('recepcion.pacientes.gestion');

    Route::get('/recepcion/pacientes/{id}/agregar-encargado', [RecepcionController::class, 'agregarEncargado'])
    ->name('recepcion.pacientes.agregarEncargado');

    Route::prefix('recepcion')->name('recepcion.')->group(function () {
        Route::get('pacientes/{id}/agregar-encargado', [RecepcionController::class, 'agregarEncargado'])
        ->name('pacientes.agregarEncargado');

        Route::get('pacientes/buscar-encargados', [RecepcionController::class, 'buscarEncargados'])
        ->name('pacientes.buscarEncargados');

        Route::post('pacientes/guardar-relacion', [RecepcionController::class, 'guardarRelacionEncargado'])
        ->name('pacientes.guardarRelacion');
    });

    Route::post('/recepcion/pacientes/seleccionar-encargado', [RecepcionController::class, 'seleccionarEncargado'])
    ->name('recepcion.pacientes.seleccionarEncargado');

    Route::get('/recepcion/pacientes/{id_paciente}/asignar-encargado', [RecepcionController::class, 'formAgregarEncargado'])
    ->name('recepcion.pacientes.formAgregarEncargado');

    Route::post('/recepcion/pacientes/guardar-relacion', [RecepcionController::class, 'guardarRelacion'])
    ->name('recepcion.pacientes.guardarRelacion');

    Route::post('/recepcion/pacientes/crear-encargado-relacion', [RecepcionController::class, 'crearEncargadoYRelacion'])
    ->name('recepcion.pacientes.crearEncargadoYRelacion');

    Route::get('/recepcion/pacientes/{id}/editar', [RecepcionController::class, 'editarPacienteForm'])
        ->name('recepcion.pacientes.editar');

    Route::put('/recepcion/pacientes/{id}', [RecepcionController::class, 'actualizarPaciente'])
        ->name('recepcion.pacientes.actualizar');

    Route::get('/recepcion/ver-pacientes', [PacienteController::class, 'verPacientes'])
        ->name('recepcion.verPacientes');

    Route::get('/recepcion/buscar', [RecepcionController::class, 'vistaBuscar'])
        ->name('recepcion.buscar');

    Route::get('/recepcion/api/encargados', [RecepcionController::class, 'apiEncargados'])
        ->name('recepcion.api.encargados');

    Route::patch('/recepcion/api/encargados/{id}', [RecepcionController::class, 'apiEncargadoQuickUpdate'])
        ->name('recepcion.api.encargados.quick');

    Route::patch('/recepcion/api/encargados/{id}/full', [RecepcionController::class, 'apiEncargadoUpdateFull'])
        ->name('recepcion.api.encargados.update');

    Route::get('/recepcion', [\App\Http\Controllers\RecepDashboardController::class, 'index'])
        ->name('dashboard.recepcion');
});

// ====================================================================================================
// Rutas para medico
// ====================================================================================================
Route::middleware(['auth', 'active', 'role:medico'])->group(function () {


    // Vista principal del panel del médico
    Route::view('/medico', 'dashboards.MedicoDashboard')
    ->name('dashboard.medico');

    // Buscar pacientes por nombre o DNI
    Route::get('/medico/consulta/buscar', [MedicoController::class, 'buscarPacientes'])
    ->name('medico.consulta.buscar');

    // Obtener signos vitales más recientes del paciente
    Route::get('/medico/signos/{paciente_id}', [MedicoController::class, 'buscarSignosVitales'])
    ->name('medico.signos.buscar');

    Route::post('/medico/guardar-solo-consulta', [MedicoController::class, 'guardarConsultaMedica'])
    ->name('medico.consulta.soloConsulta');

    Route::post('/medico/guardar-expediente', [MedicoController::class, 'guardarExpediente'])
    ->name('medico.expediente.guardar');


    // Objeto de mejora
    Route::get('/medico/asignar-examenes/{id}', [MedicoController::class, 'vistaAsignarExamenes'])
    ->name('medico.expediente.asignar');

    Route::get('/medico/expediente/{id}/asignar-examenes', [MedicoController::class, 'vistaAsignarExamenes'])
    ->name('medico.expediente.asignar');

    Route::post('/medico/expediente/{id}/guardar-examenes', [MedicoController::class, 'guardarExamenes'])
    ->name('medico.examenes.guardar');

    //

    Route::get('/medico/vista-expediente', [MedicoController::class, 'vistaRegistrarExpediente'])
    ->name('medico.expediente.vista');

    Route::get('/medico/registrar-consulta', [MedicoController::class, 'vistaRegistrarConsulta'])
    ->name('medico.consulta.form');

    Route::post('/medico/guardar-consulta', [MedicoController::class, 'guardarConsultaMedica'])
    ->name('medico.consulta.soloConsulta');

    Route::get('/medico/buscar-examenes', [MedicoController::class, 'buscarExamenes'])
    ->name('medico.examenes.buscar');
    
    

    

    // Buscar exámenes (JSON) para el buscador integrado en la misma vista
    Route::get('/medico/examenes/buscar', [MedicoController::class, 'buscarExamenes'])
    ->name('medico.examenes.buscar');

    // routes/web.php
    Route::get('/medico/buscar-examenes', function (Request $request) {
        $query = $request->input('buscar');
        $categoria_id = $request->input('categoria_id');

        $examenes = Examen::with('categoria')
            ->when($query, fn($q) => $q->where('nombre_examen', 'like', "%$query%"))
            ->when($categoria_id, fn($q) => $q->where('categoria_id', $categoria_id))
            ->orderBy('nombre_examen')
            ->get()
            ->map(fn($e) => [
                'id_examen' => $e->id_examen,
                'nombre_examen' => $e->nombre_examen,
                'nombre_categoria' => $e->categoria->nombre_categoria ?? 'Sin categoría',
            ]);

        return response()->json($examenes);
    });

    Route::get('/medico/examenes', [ExamenController::class, 'index'])
        ->name('medico.examenes.index'); 

    Route::get('/medico', [\App\Http\Controllers\MedicoDashboardController::class, 'index'])
        ->name('dashboard.medico');

    // ============================================
    // RUTAS PARA RECETAS MÉDICAS (MÉDICO - CRUD COMPLETO)
    // ============================================
    
    // Crear receta desde expediente
    Route::get('/medico/recetas/crear/{expediente}', [RecetaController::class, 'crear'])
        ->name('medico.recetas.crear');
    
    // Guardar nueva receta
    Route::post('/medico/recetas/{expediente}', [RecetaController::class, 'store'])
        ->name('medico.recetas.store');
    
    // Ver receta (detalle) - médico ve desde su namespace
    Route::get('/medico/recetas/{receta}', [RecetaController::class, 'show'])
        ->name('medico.recetas.ver');
    
    // Editar receta (formulario)
    Route::get('/medico/recetas/{receta}/editar', [RecetaController::class, 'editar'])
        ->name('medico.recetas.editar');
    
    // Actualizar receta
    Route::put('/medico/recetas/{receta}', [RecetaController::class, 'update'])
        ->name('medico.recetas.update');
    
    // Eliminar receta
    Route::delete('/medico/recetas/{receta}', [RecetaController::class, 'destroy'])
        ->name('medico.recetas.destroy');
    
    // Imprimir receta (vista especial para impresión) - médico
    Route::get('/medico/recetas/{receta}/imprimir', [RecetaController::class, 'imprimir'])
        ->name('medico.recetas.imprimir');
    
    // API: Buscar recetas por paciente (JSON)
    Route::get('/medico/recetas/buscar-por-paciente', [RecetaController::class, 'buscarPorPaciente'])
        ->name('medico.recetas.buscar-por-paciente');
    
    // API: Obtener receta por expediente (JSON)
    Route::get('/medico/recetas/por-expediente/{expediente}', [RecetaController::class, 'porExpediente'])
    ->name('medico.recetas.por-expediente');

        // Ruta para ver exámenes nueva vista
    Route::get('/medico/examenes/ver/{id}', [ExamenesConsultaController::class, 'verExamenes'])
    ->name('medico.ver-examenes');

    Route::get('/medico/expedientes/{id}/gestionar', [MedicoController::class, 'gestionarExpediente'])
    ->name('medico.expedientes.gestionar');

    // Ruta para la función dinámica (POST)
    Route::post('/medico/expediente/{id}/asignar-examenes-dinamico', [MedicoController::class, 'guardarExamenesDinamico'])
    ->name('medico.examenes.guardar.dinamico');

    // Ruta para la vista (GET) - usa la misma función que ya tienes
    Route::get('/medico/expediente/{id}/asignar-examenes', [MedicoController::class, 'vistaAsignarExamenes'])
    ->name('medico.expediente.asignar');

    Route::get('/medico/examenes/ver/{id}', [ExamenesConsultaController::class, 'verExamenes'])
    ->name('medico.ver-examenes');

});

require __DIR__.'/auth.php';