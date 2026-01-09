<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExpedientesExamenesExport implements
    FromCollection, WithHeadings, ShouldAutoSize, WithStyles, WithEvents
{
    public function collection(): Collection
    {
        // --- Subconsulta: exámenes por EXPEDIENTE ---
        $aggExp = DB::table('expediente_examen as ee')
            ->join('examenes_medicos as em', 'em.id_examen_medico', '=', 'ee.examen_medico_id')
            ->join('examenes as ex', 'ex.id_examen', '=', 'em.examen_id')
            ->leftJoin('categorias as c', 'c.id_categoria', '=', 'ex.categoria_id')
            ->selectRaw("
                ee.expediente_id,
                COUNT(*) as total_examenes,
                DATE_FORMAT(MIN(em.fecha_asignacion),'%Y-%m-%d %H:%i') as primera_asignacion,
                DATE_FORMAT(MAX(em.fecha_asignacion),'%Y-%m-%d %H:%i') as ultima_asignacion,
                GROUP_CONCAT(
                  CONCAT(
                    COALESCE(c.nombre_categoria,'Sin categoría'),
                    ': ',
                    ex.nombre_examen,
                    ' [',
                    DATE_FORMAT(em.fecha_asignacion,'%Y-%m-%d %H:%i'),
                    ']'
                  )
                  ORDER BY c.nombre_categoria, ex.nombre_examen
                  SEPARATOR ' | '
                ) as detalle_examenes
            ")
            ->groupBy('ee.expediente_id');

        // --- Subconsulta: exámenes por CONSULTA (fallback) ---
        $aggCons = DB::table('examenes_medicos as em')
            ->join('examenes as ex', 'ex.id_examen', '=', 'em.examen_id')
            ->leftJoin('categorias as c', 'c.id_categoria', '=', 'ex.categoria_id')
            ->selectRaw("
                em.consulta_id,
                COUNT(*) as total_examenes,
                DATE_FORMAT(MIN(em.fecha_asignacion),'%Y-%m-%d %H:%i') as primera_asignacion,
                DATE_FORMAT(MAX(em.fecha_asignacion),'%Y-%m-%d %H:%i') as ultima_asignacion,
                GROUP_CONCAT(
                  CONCAT(
                    COALESCE(c.nombre_categoria,'Sin categoría'),
                    ': ',
                    ex.nombre_examen,
                    ' [',
                    DATE_FORMAT(em.fecha_asignacion,'%Y-%m-%d %H:%i'),
                    ']'
                  )
                  ORDER BY c.nombre_categoria, ex.nombre_examen
                  SEPARATOR ' | '
                ) as detalle_examenes
            ")
            ->groupBy('em.consulta_id');

        // --- Consulta principal (1 fila por expediente) ---
        $rows = DB::table('expedientes as e')
            ->join('pacientes as p', 'p.id_paciente', '=', 'e.paciente_id')
            ->join('personas as pp', 'pp.id_persona', '=', 'p.persona_id')
            ->leftJoin('encargados as enc', 'enc.id_encargado', '=', 'e.encargado_id')
            ->leftJoin('personas as pe', 'pe.id_persona', '=', 'enc.persona_id')
            ->join('empleados as enf', 'enf.id_empleado', '=', 'e.enfermera_id')
            ->join('empleados as doc', 'doc.id_empleado', '=', 'e.doctor_id')
            ->join('signos_vitales as sv', 'sv.id_signos_vitales', '=', 'e.signos_vitales_id')
            ->leftJoin('consulta_doctor as c', 'c.id_consulta_doctor', '=', 'e.consulta_id')

            // subconsultas
            ->leftJoinSub($aggExp, 'agg_ee', function ($join) {
                $join->on('agg_ee.expediente_id', '=', 'e.id_expediente');
            })
            ->leftJoinSub($aggCons, 'agg_cons', function ($join) {
                $join->on('agg_cons.consulta_id', '=', 'e.consulta_id');
            })

            ->selectRaw("
                -- Expediente
                e.codigo,
                DATE_FORMAT(e.fecha_creacion,'%Y-%m-%d %H:%i') as fecha_creacion,
                e.estado,
                e.motivo_ingreso,
                e.diagnostico,
                e.observaciones,

                -- Paciente
                pp.nombre as paciente_nombre,
                pp.apellido as paciente_apellido,
                pp.dni as paciente_dni,
                pp.edad as paciente_edad,
                pp.sexo as paciente_sexo,
                pp.telefono as paciente_telefono,
                pp.direccion as paciente_direccion,

                -- Encargado (puede ser NULL)
                pe.nombre as encargado_nombre,
                pe.apellido as encargado_apellido,
                pe.dni as encargado_dni,
                pe.telefono as encargado_telefono,
                pe.direccion as encargado_direccion,

                -- Enfermera
                enf.nombre as enfermera_nombre,
                enf.apellido as enfermera_apellido,
                enf.dni as enfermera_dni,

                -- Doctor
                doc.nombre as doctor_nombre,
                doc.apellido as doctor_apellido,
                doc.dni as doctor_dni,

                -- Signos vitales
                sv.presion_arterial,
                sv.fc,
                sv.fr,
                sv.temperatura,
                sv.so2,
                sv.peso,
                sv.glucosa,
                DATE_FORMAT(sv.fecha_registro,'%Y-%m-%d %H:%i') as fecha_signos,

                -- Consulta
                c.resumen_clinico,
                c.impresion_diagnostica,
                c.indicaciones,
                c.urgencia,
                c.tipo_urgencia,
                c.resultado,
                c.citado,
                c.firma_sello,

                -- Exámenes (usar agg_ee si existe; de lo contrario agg_cons)
                COALESCE(agg_ee.total_examenes, agg_cons.total_examenes, 0) as total_examenes,
                COALESCE(agg_ee.primera_asignacion, agg_cons.primera_asignacion) as primera_asignacion,
                COALESCE(agg_ee.ultima_asignacion, agg_cons.ultima_asignacion) as ultima_asignacion,
                COALESCE(agg_ee.detalle_examenes, agg_cons.detalle_examenes) as detalle_examenes
            ")
            ->orderBy('e.id_expediente')
            ->get();

        return collect($rows);
    }

    public function headings(): array
    {
        return [
            'Expediente Código','Fecha Creación','Estado','Motivo Ingreso','Diagnóstico','Observaciones',
            'Paciente Nombre','Paciente Apellido','Paciente DNI','Paciente Edad','Paciente Sexo','Paciente Teléfono','Paciente Dirección',
            'Encargado Nombre','Encargado Apellido','Encargado DNI','Encargado Teléfono','Encargado Dirección',
            'Enfermera Nombre','Enfermera Apellido','Enfermera DNI',
            'Doctor Nombre','Doctor Apellido','Doctor DNI',
            'Presión Arterial','FC','FR','Temperatura','SO2','Peso (kg)','Glucosa','Fecha Signos',
            'Resumen Clínico','Impresión Diagnóstica','Indicaciones','Urgencia','Tipo Urgencia','Resultado','Citado','Firma/Sello',
            'Total Exámenes','Primera Asignación','Última Asignación','Exámenes (Categoría: Examen [fecha])',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastCol = $sheet->getHighestColumn();
        $sheet->getStyle("A1:{$lastCol}1")->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '4F46E5']],
        ]);
        return [];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $hoja = $event->sheet->getDelegate();
                $lastCol = $hoja->getHighestColumn();
                $lastRow = $hoja->getHighestRow();

                $hoja->setAutoFilter("A1:{$lastCol}1");
                $hoja->freezePane('A2');

                $hoja->getStyle("A1:{$lastCol}{$lastRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['rgb' => 'B3BCF5'],
                        ],
                    ],
                ]);

                // Anchos útiles para textos largos
                $hoja->getColumnDimension('F')->setWidth(28);   // Observaciones
                $hoja->getColumnDimension('AI')->setWidth(30);  // Resumen Clínico
                $hoja->getColumnDimension('AJ')->setWidth(30);  // Impresión Diagnóstica
                $hoja->getColumnDimension('AK')->setWidth(30);  // Indicaciones
                $hoja->getColumnDimension($lastCol)->setWidth(80); // Detalle exámenes
            },
        ];
    }
}
