<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Paciente;
use App\Models\Expediente;
use App\Models\Examen;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;


class ExpedienteController extends Controller
{

    public function read($id)
    {
        $expediente = \App\Models\Expediente::with([
            'paciente.persona',
            'paciente.relacionesConEncargado.encargado.persona', // para derivar desde pivot
            'encargado.persona',
            'enfermera',
            'doctor',
            'signosVitales',
            'consulta.examenesMedicos.examen.categoria',
        ])->findOrFail($id);

        return view('logica.admin.read-expediente', compact('expediente'));
    }

    public function index(Request $request)
    {
        $buscar = trim((string) $request->input('buscar', ''));

        $pacientes = Paciente::with([
            'persona',
            'expedientes.doctor',
            'expedientes.encargado.persona',
            'expedientes.enfermera',
            'expedientes.signosVitales',
            'expedientes.consulta',
            'expedientes.receta', 
        ])
            ->when($buscar !== '', function ($query) use ($buscar) {
                $like = '%' . str_replace(' ', '%', $buscar) . '%';

                $query->where(function ($q) use ($like) {
                    // Buscar por datos de la persona
                    $q->whereHas('persona', function ($qq) use ($like) {
                        $qq->where('nombre', 'like', $like)
                            ->orWhere('apellido', 'like', $like)
                            ->orWhere('dni', 'like', $like);
                    })
                        // Buscar también por código de expediente
                        ->orWhereHas('expedientes', function ($qq) use ($like) {
                            $qq->where('codigo', 'like', $like);
                        });
                });
            })
            ->orderBy('id_paciente', 'asc')
            ->paginate(50)
            ->withQueryString();

        return view('logica.admin.gestionar-expedientes', [
            'pacientes' => $pacientes,
            'buscar'    => $buscar,
        ]);
    }



    /**
     * Eliminar expedientes asociados a un paciente.
     */
    public function eliminar($id)
    {
        $paciente = Paciente::with('expedientes')->findOrFail($id);

        foreach ($paciente->expedientes as $expediente) {
            $expediente->examenesMedicos()->detach(); // elimina relaciones en tabla pivot
            $expediente->delete(); // elimina expediente
        }

        return back()->with('success', 'Todos los expedientes del paciente fueron eliminados correctamente.');
    }

    /**
     * Mostrar expediente completo con todos los datos relacionados.
     */
    public function ver($id)
    {
        $expediente = Expediente::with([
            'paciente.persona',
            'paciente.relacionesConEncargado.encargado.persona', // para derivar desde pivot
            'encargado.persona',
            'enfermera',
            'doctor',
            'signosVitales',
            'consulta.examenesMedicos.examen.categoria'
        ])->findOrFail($id);

        return view('logica.admin.expediente-completo', compact('expediente'));
    }

    public function expedienteCompleto($id)
    {
        $expediente = Expediente::with([
            'paciente.persona',
            'paciente.relacionesConEncargado.encargado.persona', // para derivar desde pivot
            'encargado.persona',
            'enfermera',
            'doctor',
            'signosVitales',
            'consulta.examenesMedicos.examen.categoria'
        ])->findOrFail($id);

        return view('logica.admin.expediente-completo', compact('expediente'));
    }

    public function edit($id)
    {
        $expediente = \App\Models\Expediente::with([
            'paciente.persona',
            'paciente.relacionesConEncargado.encargado.persona', // para derivar desde pivot
            'encargado.persona',
            'enfermera',
            'doctor',
            'signosVitales',
            'consulta.examenesMedicos.examen.categoria',
        ])->findOrFail($id);

        // Catálogo de exámenes para agregar
        $catalogoExamenes = Examen::with('categoria')
            ->orderBy('nombre_examen')
            ->get();

        return view('logica.admin.editar-expediente', compact('expediente', 'catalogoExamenes'));
    }


    public function update(Request $request, $id)
{
    // Validación para campos del expediente
    $request->validate([
        'estado'         => 'required|in:abierto,cerrado',
        'motivo_ingreso' => 'nullable|string',
        'diagnostico'    => 'nullable|string',
        'observaciones'  => 'nullable|string',
        // Campos de la consulta doctor
        'resumen_clinico'      => 'nullable|string',
        'impresion_diagnostica' => 'nullable|string',
        'indicaciones'         => 'nullable|string',
        'urgencia'             => 'nullable|in:si,no',
        'tipo_urgencia'        => 'nullable|string',
        'resultado'            => 'nullable|string',
        'citado'               => 'nullable|date',
        'firma_sello'          => 'nullable|in:si,no',
    ]);

    $expediente = \App\Models\Expediente::with('consulta')->findOrFail($id);
    
    // Actualizar expediente
    $expediente->estado         = $request->estado;
    $expediente->motivo_ingreso = $request->motivo_ingreso;
    $expediente->diagnostico    = $request->diagnostico;
    $expediente->observaciones  = $request->observaciones;
    $expediente->save();

    // Actualizar consulta doctor relacionada (si existe)
    if ($expediente->consulta) {
        $expediente->consulta->update([
            'resumen_clinico'       => $request->resumen_clinico,
            'impresion_diagnostica' => $request->impresion_diagnostica,
            'indicaciones'          => $request->indicaciones,
            'urgencia'              => $request->urgencia,
            'tipo_urgencia'         => $request->tipo_urgencia,
            'resultado'             => $request->resultado,
            'citado'                => $request->citado,
            'firma_sello'           => $request->firma_sello,
        ]);
    }

    return redirect()
        ->route('expedientes.index')
        ->with('success', 'El expediente ' . $expediente->codigo . ' fue actualizado correctamente.');
}

    public function pdf($id)
    {
        $expediente = Expediente::with([
            'paciente.persona',
            'paciente.relacionesConEncargado.encargado.persona', // para derivar desde pivot
            'encargado.persona',
            'enfermera',
            'doctor',
            'signosVitales',
            'consulta.examenesMedicos.examen.categoria',
        ])->findOrFail($id);

        // Embebemos logos para que Dompdf los vea sin rutas externas
        $logo1Path = public_path('images/logo1.png');
        $logo2Path = public_path('images/logo2.png');

        $logo1 = is_file($logo1Path) ? 'data:image/png;base64,' . base64_encode(file_get_contents($logo1Path)) : null;
        $logo2 = is_file($logo2Path) ? 'data:image/png;base64,' . base64_encode(file_get_contents($logo2Path)) : null;

        // Renderizamos EXACTAMENTE la misma vista
        $pdf = Pdf::loadView('logica.admin.expediente-completo', [
            'expediente' => $expediente,
            'isPdf'      => true,
            'logo1'      => $logo1,
            'logo2'      => $logo2,
        ])
            ->setPaper('letter', 'portrait')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled'      => true,
                'dpi'                  => 96,
                'defaultFont'          => 'DejaVu Sans',
            ]);

        // Nombre: NombreApellido_CODIGO.pdf (con guion bajo y sin acentos)
        $p        = $expediente->paciente->persona;
        $paciente = trim(($p->nombre ?? '') . ' ' . ($p->apellido ?? ''));
        $safe     = Str::slug($paciente ?: 'Paciente', '_');  // usa _ como separador
        $filename = $safe . '_' . $expediente->codigo . '.pdf';

        return $pdf->download($filename); // o ->stream($filename) si prefieres abrir en el navegador
    }
}
