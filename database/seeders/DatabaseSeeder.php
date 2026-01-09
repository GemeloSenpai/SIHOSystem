<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Persona;
use App\Models\Empleado;
use App\Models\Paciente;
use App\Models\Categoria;
use App\Models\Examen;
use App\Models\Encargado;
use App\Models\SignosVitales;
use App\Models\ConsultaDoctor;
use App\Models\Expediente;
use App\Models\ExamenMedico;
use App\Models\Receta;

class DatabaseSeeder extends Seeder
{
    // Cantidades para 1000 registros principales
    private $cantidades = [
        'categorias' => 15,
        'examenes' => 30,
        'medicos' => 10,
        'enfermeros' => 15,
        'recepcionistas' => 5,
        'pacientes' => 1000,
        'encargados' => 300,
        'expedientes' => 800,
        'examenes_medicos' => 2000,
        'recetas' => 500,
    ];

    public function run(): void
    {
        $this->command->info('🚀 INICIANDO POBLADO COMPLETO DEL SISTEMA HOSPITALARIO');
        $this->command->info('═══════════════════════════════════════════════════');

        DB::beginTransaction();

        try {
            // ============ 1. ADMINISTRADOR ============
            $this->command->info('👑 Creando administrador...');
            User::create([
                'name' => 'Administrador SIHO',
                'email' => 'admin@siho.com',
                'email_verified_at' => now(),
                'password' => bcrypt('password123'),
                'role' => 'admin',
                'estado' => 'activo',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // ============ 2. CATEGORÍAS (ÚNICAS) ============
            $this->command->info("📋 Creando {$this->cantidades['categorias']} categorías...");
            $categoriasNombres = [
                'Hematología',
                'Bioquímica Sanguínea',
                'Microbiología',
                'Inmunología',
                'Orina',
                'Heces',
                'Radiología',
                'Ultrasonido',
                'Tomografía',
                'Electrocardiograma',
                'Ecocardiograma',
                'Endoscopia',
                'Neurología',
                'Cardiología',
                'Gastroenterología',
                'Nefrología',
                'Urología',
                'Ginecología',
                'Pediatría',
                'Dermatología',
                'Oncología',
                'Psiquiatría',
                'Oftalmología',
                'Otorrinolaringología',
                'Traumatología'
            ];

            $categorias = [];
            foreach (array_slice($categoriasNombres, 0, $this->cantidades['categorias']) as $nombre) {
                $categorias[] = Categoria::create(['nombre_categoria' => $nombre]);
            }

            // ============ 3. EXÁMENES (NOMBRES ÚNICOS) ============
            $this->command->info("🔬 Creando {$this->cantidades['examenes']} exámenes...");
            $examenesBase = [
                'Hemograma',
                'Plaquetas',
                'VSG',
                'Glucosa',
                'Colesterol Total',
                'Triglicéridos',
                'Creatinina',
                'Ácido Úrico',
                'Rayos X de Tórax',
                'Ultrasonido Abdominal',
                'Tomografía Cerebral',
                'ECG de Reposo',
                'Endoscopia Digestiva',
                'Urocultivo',
                'Coprocultivo',
                'TGO/AST',
                'TGP/ALT',
                'Bilirrubina Total',
                'Proteínas Totales',
                'Albumina',
                'Calcio Sérico',
                'Fósforo',
                'Hierro',
                'Ferritina',
                'Vitamina D',
                'TSH',
                'T4 Libre',
                'PSA',
                'Frotis de Sangre',
                'Grupo Sanguíneo'
            ];

            $examenes = [];
            $examenesUsados = [];

            for ($i = 1; $i <= $this->cantidades['examenes']; $i++) {
                $nombreBase = $examenesBase[array_rand($examenesBase)];
                $especificacion = ['Completo', 'Básico', 'Avanzado', 'Perfil', 'De Control'][rand(0, 4)];
                $categoria = $categorias[array_rand($categorias)];

                $nombreExamen = "{$nombreBase} {$especificacion} #{$i}";

                // Asegurar único
                while (in_array($nombreExamen, $examenesUsados)) {
                    $nombreExamen = "{$nombreBase} {$especificacion} #" . rand(1000, 9999);
                }

                $examen = Examen::create([
                    'nombre_examen' => $nombreExamen,
                    'categoria_id' => $categoria->id_categoria,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $examenes[] = $examen;
                $examenesUsados[] = $nombreExamen;
            }

            $this->command->info("   ✅ {$this->cantidades['examenes']} exámenes creados");

            // ============ 4. PERSONAL MÉDICO ============
            $this->command->info('👥 Creando personal médico...');

            $medicos = [];
            $enfermeros = [];
            $recepcionistas = [];

            // Médicos
            for ($i = 1; $i <= $this->cantidades['medicos']; $i++) {
                $user = User::create([
                    'name' => "Dr. Médico {$i}",
                    'email' => "medico{$i}@siho.com",
                    'password' => bcrypt('password'),
                    'role' => 'medico',
                    'estado' => 'activo',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $empleado = Empleado::create([
                    'user_id' => $user->id,
                    'nombre' => "Médico",
                    'apellido' => "{$i}",
                    'edad' => rand(30, 60),
                    'fecha_nacimiento' => now()->subYears(rand(30, 60)),
                    'dni' => 'MED-' . str_pad($i, 5, '0', STR_PAD_LEFT),
                    'sexo' => rand(0, 1) ? 'M' : 'F',
                    'direccion' => "Dirección Médico {$i}",
                    'telefono' => $this->generarTelefono(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $medicos[] = $empleado;
            }

            // Enfermeros
            for ($i = 1; $i <= $this->cantidades['enfermeros']; $i++) {
                $user = User::create([
                    'name' => "Enf. Enfermero {$i}",
                    'email' => "enfermero{$i}@siho.com",
                    'password' => bcrypt('password'),
                    'role' => 'enfermero',
                    'estado' => 'activo',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $empleado = Empleado::create([
                    'user_id' => $user->id,
                    'nombre' => "Enfermero",
                    'apellido' => "{$i}",
                    'edad' => rand(25, 55),
                    'fecha_nacimiento' => now()->subYears(rand(25, 55)),
                    'dni' => 'ENF-' . str_pad($i, 5, '0', STR_PAD_LEFT),
                    'sexo' => rand(0, 1) ? 'M' : 'F',
                    'direccion' => "Dirección Enfermero {$i}",
                    'telefono' => $this->generarTelefono(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $enfermeros[] = $empleado;
            }

            $this->command->info("   ✅ {$this->cantidades['medicos']} médicos creados");
            $this->command->info("   ✅ {$this->cantidades['enfermeros']} enfermeros creados");

            // ============ 5. 1000 PACIENTES ============
            $this->command->info("🏥 Creando {$this->cantidades['pacientes']} pacientes...");

            $pacientes = [];
            $pacientesCreados = 0;

            for ($i = 1; $i <= $this->cantidades['pacientes']; $i++) {
                $persona = Persona::create([
                    'nombre' => "Paciente" . $i,
                    'apellido' => "Apellido" . $i,
                    'edad' => rand(18, 90),
                    'fecha_nacimiento' => now()->subYears(rand(18, 90)),
                    'dni' => $this->generarDNI($i),
                    'sexo' => rand(0, 1) ? 'M' : 'F',
                    'direccion' => "Dirección #" . $i,
                    'telefono' => $this->generarTelefono(),
                    'created_at' => now()->subDays(rand(0, 365)),
                    'updated_at' => now(),
                ]);

                $codigo = 'PAC-' . date('Y') . '-' . str_pad($i, 6, '0', STR_PAD_LEFT);

                $paciente = Paciente::create([
                    'persona_id' => $persona->id_persona,
                    'codigo_paciente' => $codigo,
                    'created_at' => now()->subDays(rand(0, 365)),
                    'updated_at' => now(),
                ]);

                $pacientes[] = $paciente;
                $pacientesCreados++;

                if ($pacientesCreados % 200 == 0) {
                    $this->command->info("   📊 {$pacientesCreados}/{$this->cantidades['pacientes']} pacientes");
                }
            }

            // ============ 6. ENCARGADOS ============
            $this->command->info("👥 Creando {$this->cantidades['encargados']} encargados...");

            $encargados = [];
            for ($i = 1; $i <= $this->cantidades['encargados']; $i++) {
                $persona = Persona::create([
                    'nombre' => "Encargado" . $i,
                    'apellido' => "Apellido" . $i,
                    'edad' => rand(25, 70),
                    'fecha_nacimiento' => now()->subYears(rand(25, 70)),
                    'dni' => 'ENC-' . str_pad($i, 8, '0', STR_PAD_LEFT),
                    'sexo' => rand(0, 1) ? 'M' : 'F',
                    'direccion' => "Dirección Encargado #" . $i,
                    'telefono' => $this->generarTelefono(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $encargado = Encargado::create([
                    'persona_id' => $persona->id_persona,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $encargados[] = $encargado;
            }

            // ============ RELACIONES PACIENTE-ENCARGADO (VISITAS) - VERSIÓN MEJORADA ============
            $this->command->info('👥 Creando visitas de pacientes...');
            $this->call(RelacionPacienteEncargadoTableSeeder::class);


            // ============ 7. SIGNOS VITALES (3 por paciente) ============
            $this->command->info('💓 Creando signos vitales...');

            $signosCreados = 0;
            $signosVitalesPorPaciente = [];

            foreach ($pacientes as $paciente) {
                $signosPaciente = [];
                for ($j = 0; $j < 3; $j++) {
                    $sistolica = rand(90, 180);
                    $diastolica = rand(50, 120);

                    $signo = SignosVitales::create([
                        'paciente_id' => $paciente->id_paciente,
                        'enfermera_id' => $enfermeros[array_rand($enfermeros)]->id_empleado,
                        'presion_arterial' => $sistolica . '/' . $diastolica,
                        'fc' => rand(50, 120),
                        'fr' => rand(10, 25),
                        'temperatura' => round(rand(355, 395) / 10, 1),
                        'so2' => rand(90, 100),
                        'peso' => round(rand(400, 1200) / 10, 1),
                        'glucosa' => round(rand(700, 2500) / 10, 1),
                        'fecha_registro' => now()->subDays(rand(0, 30)),
                        'created_at' => now()->subDays(rand(0, 30)),
                        'updated_at' => now(),
                    ]);

                    $signosPaciente[] = $signo;
                    $signosCreados++;
                }
                $signosVitalesPorPaciente[$paciente->id_paciente] = $signosPaciente;

                if ($signosCreados % 1500 == 0) {
                    $this->command->info("   📈 {$signosCreados} signos creados");
                }
            }

            $this->command->info("   ✅ {$signosCreados} signos vitales creados");

            // ============ 8. CONSULTAS MÉDICAS (2 por paciente) ============
            $this->command->info('👨‍⚕️ Creando consultas médicas...');

            $consultas = [];
            $consultasCreadas = 0;

            foreach ($pacientes as $paciente) {
                $signosPaciente = $signosVitalesPorPaciente[$paciente->id_paciente] ?? [];

                for ($j = 0; $j < 2; $j++) {
                    if (isset($signosPaciente[$j])) {
                        $consulta = ConsultaDoctor::create([
                            'paciente_id' => $paciente->id_paciente,
                            'doctor_id' => $medicos[array_rand($medicos)]->id_empleado,
                            'signos_vitales_id' => $signosPaciente[$j]->id_signos_vitales,
                            'resumen_clinico' => $this->getRandomMotivo(),
                            'impresion_diagnostica' => $this->getRandomDiagnostico(),
                            'indicaciones' => $this->getRandomIndicaciones(),
                            'urgencia' => rand(0, 4) == 0 ? 'si' : 'no', // 20% urgencia
                            'tipo_urgencia' => rand(0, 4) == 0 ? ['medica', 'pediatrica', 'quirurgico', 'gineco obstetrica'][rand(0, 3)] : null,
                            'resultado' => ['alta', 'seguimiento', 'referido'][rand(0, 2)],
                            'citado' => rand(0, 1) ? now()->addDays(rand(7, 30)) : null,
                            'firma_sello' => rand(0, 1) ? 'si' : 'no',
                            'created_at' => now()->subDays(rand(0, 180)),
                            'updated_at' => now(),
                        ]);

                        $consultas[] = $consulta;
                        $consultasCreadas++;
                    }
                }

                if ($consultasCreadas % 500 == 0) {
                    $this->command->info("   📈 {$consultasCreadas} consultas creadas");
                }
            }

            $this->command->info("   ✅ {$consultasCreadas} consultas médicas creadas");

            // ============ 9. EXPEDIENTES (1 por paciente) ============
            $this->command->info("📁 Creando {$this->cantidades['expedientes']} expedientes...");

            $expedientes = [];
            $expedientesCreados = 0;

            foreach (array_slice($pacientes, 0, $this->cantidades['expedientes']) as $index => $paciente) {
                $signosPaciente = $signosVitalesPorPaciente[$paciente->id_paciente] ?? [];
                $consultasPaciente = array_filter($consultas, fn($c) => $c->paciente_id == $paciente->id_paciente);
                $consulta = !empty($consultasPaciente) ? reset($consultasPaciente) : null;

                if (!empty($signosPaciente)) {
                    $expediente = Expediente::create([
                        'paciente_id' => $paciente->id_paciente,
                        'encargado_id' => !empty($encargados) ? $encargados[array_rand($encargados)]->id_encargado : null,
                        'enfermera_id' => $enfermeros[array_rand($enfermeros)]->id_empleado,
                        'signos_vitales_id' => $signosPaciente[0]->id_signos_vitales,
                        'doctor_id' => $medicos[array_rand($medicos)]->id_empleado,
                        'consulta_id' => $consulta ? $consulta->id_consulta_doctor : null,
                        'fecha_creacion' => now()->subDays(rand(0, 365)),
                        'codigo' => 'EXP-' . date('Y') . '-' . str_pad($index + 1, 6, '0', STR_PAD_LEFT),
                        'estado' => rand(0, 1) ? 'abierto' : 'cerrado',
                        'motivo_ingreso' => $this->getRandomMotivo(),
                        'diagnostico' => $this->getRandomDiagnostico(),
                        'observaciones' => 'Paciente en seguimiento. ' . ['Estable', 'Mejorando', 'Requiere atención'][rand(0, 2)],
                        'created_at' => now()->subDays(rand(0, 365)),
                        'updated_at' => now(),
                    ]);

                    $expedientes[] = $expediente;
                    $expedientesCreados++;

                    if ($expedientesCreados % 200 == 0) {
                        $this->command->info("   📈 {$expedientesCreados}/{$this->cantidades['expedientes']} expedientes");
                    }
                }
            }

            $this->command->info("   ✅ {$expedientesCreados} expedientes creados");

            // ============ 10. EXÁMENES MÉDICOS ============
            $this->command->info("🔬 Creando {$this->cantidades['examenes_medicos']} exámenes médicos...");

            $examenesMedicos = [];
            $examenesMedicosCreados = 0;

            for ($i = 0; $i < $this->cantidades['examenes_medicos']; $i++) {
                $consulta = $consultas[array_rand($consultas)];
                $examen = $examenes[array_rand($examenes)];

                // Verificar que no exista ya
                $existe = ExamenMedico::where([
                    'paciente_id' => $consulta->paciente_id,
                    'consulta_id' => $consulta->id_consulta_doctor,
                    'examen_id' => $examen->id_examen
                ])->exists();

                if (!$existe) {
                    $examenMedico = ExamenMedico::create([
                        'paciente_id' => $consulta->paciente_id,
                        'doctor_id' => $consulta->doctor_id,
                        'consulta_id' => $consulta->id_consulta_doctor,
                        'examen_id' => $examen->id_examen,
                        'fecha_asignacion' => $consulta->created_at ?? now(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    $examenesMedicos[] = $examenMedico;
                    $examenesMedicosCreados++;

                    if ($examenesMedicosCreados % 500 == 0) {
                        $this->command->info("   📈 {$examenesMedicosCreados}/{$this->cantidades['examenes_medicos']} exámenes médicos");
                    }
                }
            }

            $this->command->info("   ✅ {$examenesMedicosCreados} exámenes médicos creados");

            // ============ X. RELACIONES EXPEDIENTE-EXAMEN ============
            $this->command->info('📋 Relacionando expedientes con exámenes...');
            $this->call(ExpedienteExamenTableSeeder::class);

            // ============ X+1. CORREGIR TIPO CONSULTA (si es necesario) ============
            $this->command->info('🔧 Corrigiendo tipos de consulta nulos...');
            DB::table('relacion_paciente_encargado')
                ->whereNull('tipo_consulta')
                ->update(['tipo_consulta' => 'general']);

            // ============ 11. RECETAS ============
            $this->command->info("💊 Creando {$this->cantidades['recetas']} recetas...");

            $recetasCreadas = 0;
            $expedientesParaRecetas = array_slice($expedientes, 0, min($this->cantidades['recetas'], count($expedientes)));

            foreach ($expedientesParaRecetas as $expediente) {
                $receta = Receta::create([
                    'expediente_id' => $expediente->id_expediente,
                    'paciente_id' => $expediente->paciente_id,
                    'doctor_id' => $expediente->doctor_id,
                    'creado_por' => User::where('role', 'medico')->first()->id ?? 1,
                    'fecha_prescripcion' => $expediente->fecha_creacion,
                    'diagnostico' => $expediente->diagnostico ?? 'Consulta general',
                    'receta' => $this->generarRecetaTexto(),
                    'observaciones' => 'Tomar según indicaciones. No suspender tratamiento.',
                    'edad_paciente_en_receta' => $expediente->paciente->persona->edad ?? rand(25, 70),
                    'peso_paciente_en_receta' => round(rand(500, 950) / 10, 1),
                    'alergias_conocidas' => rand(0, 3) == 0 ? 'Penicilina' : 'Ninguna',
                    'estado' => ['activa', 'completada'][rand(0, 1)],
                    'firma_digital' => rand(0, 1) ? md5(time() . $expediente->id_expediente) : null,
                    'created_at' => $expediente->fecha_creacion,
                    'updated_at' => now(),
                ]);

                $recetasCreadas++;

                if ($recetasCreadas % 100 == 0) {
                    $this->command->info("   📈 {$recetasCreadas}/{$this->cantidades['recetas']} recetas");
                }
            }

            $this->command->info("   ✅ {$recetasCreadas} recetas creadas");

            DB::commit();

            // ============ 12. RESUMEN FINAL ============
            $this->command->info('═══════════════════════════════════════════════════');
            $this->command->info('✅ POBLADO COMPLETO EXITOSO');
            $this->command->info('═══════════════════════════════════════════════════');
            $this->command->info('📊 ESTADÍSTICAS FINALES:');
            $this->command->info('👥 Usuarios: ' . User::count());
            $this->command->info('👤 Personas: ' . Persona::count());
            $this->command->info('👨‍⚕️  Empleados: ' . Empleado::count());
            $this->command->info('🏥 Pacientes: ' . count($pacientes));
            $this->command->info('📋 Categorías: ' . count($categorias));
            $this->command->info('🔬 Exámenes: ' . count($examenes));
            $this->command->info('👥 Encargados: ' . count($encargados));
            $this->command->info('💓 Signos Vitales: ' . $signosCreados);
            $this->command->info('👨‍⚕️  Consultas: ' . $consultasCreadas);
            $this->command->info('📁 Expedientes: ' . $expedientesCreados);
            $this->command->info('🔬 Exámenes Médicos: ' . $examenesMedicosCreados);
            $this->command->info('💊 Recetas: ' . $recetasCreadas);
            $this->command->info('═══════════════════════════════════════════════════');
            $this->command->info('🔑 CREDENCIALES: admin@siho.com / password123');
            $this->command->info('═══════════════════════════════════════════════════');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('❌ ERROR: ' . $e->getMessage());
            throw $e;
        }
    }

    private function generarDNI($numero): string
    {
        $fecha = now()->subYears(rand(18, 90))->format('dmY');
        return '001' . $fecha . str_pad($numero, 6, '0', STR_PAD_LEFT) . 'A';
    }

    private function generarTelefono(): string
    {
        $codigo = rand(2, 8) . str_pad(rand(0, 99), 2, '0', STR_PAD_LEFT);
        $numero = str_pad(rand(0, 9999999), 7, '0', STR_PAD_LEFT);
        return $codigo . '-' . $numero;
    }

    private function getRandomMotivo(): string
    {
        $motivos = [
            'Dolor abdominal',
            'Fiebre y malestar general',
            'Tos persistente',
            'Control de presión arterial',
            'Evaluación de diabetes',
            'Dolor de cabeza frecuente',
            'Chequeo de rutina',
            'Problemas digestivos',
            'Control post-operatorio',
            'Evaluación de resultados'
        ];
        return $motivos[array_rand($motivos)];
    }

    private function getRandomDiagnostico(): string
    {
        $diagnosticos = [
            'Gastritis aguda',
            'Hipertensión arterial controlada',
            'Infección respiratoria alta',
            'Diabetes mellitus tipo 2',
            'Ansiedad generalizada',
            'Lumbalgia mecánica',
            'Reflujo gastroesofágico',
            'Artrosis degenerativa'
        ];
        return $diagnosticos[array_rand($diagnosticos)];
    }

    private function getRandomIndicaciones(): string
    {
        $indicaciones = [
            'Reposo y líquidos abundantes',
            'Tomar medicamento cada 8 horas',
            'Dieta baja en sal',
            'Ejercicio moderado regular',
            'Control en 15 días',
            'Evitar alimentos picantes',
            'Aplicar hielo localmente',
            'Seguimiento con especialista'
        ];
        return $indicaciones[array_rand($indicaciones)];
    }

    private function generarRecetaTexto(): string
    {
        return "PRESCRIPCIÓN MÉDICA:\n\n" .
            "1. Omeprazol 20 mg - 1 tableta diaria antes del desayuno (30 días)\n" .
            "2. Suplemento multivitamínico - 1 tableta diaria con alimentos\n\n" .
            "Instrucciones: Completar tratamiento. No automedicarse.";
    }
}
