<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Examen;
use App\Models\Categoria;
use Illuminate\Validation\Rule;
use App\Models\ExamenMedico;
use Illuminate\Support\Facades\Auth;

use Throwable;

class ExamenController extends Controller
{
    public function index(Request $request)
    {
        // Traemos categorías con sus exámenes
        $categorias = Categoria::with(['examenes' => function($q) {
            $q->orderBy('nombre_examen', 'asc');
        }])->orderBy('nombre_categoria', 'asc')->get();

        return view('logica.admin.gestionar-examenes', compact('categorias'));
    }

   public function store(Request $request)
    {
        $request->validate([
            'categoria_id'  => 'required|exists:categorias,id_categoria',
            'nombre_examen' => 'required|string|unique:examenes,nombre_examen',
        ]);

        Examen::create([
            'categoria_id'  => $request->categoria_id,
            'nombre_examen' => trim($request->nombre_examen),
        ]);

        return redirect()->route('admin.gestionar.examenes')
            ->with('success', 'Examen registrado correctamente.');
    }

    public function update(Request $request, $id)
    {
        $examen = Examen::findOrFail($id);

        $request->validate([
            'categoria_id'  => ['required','exists:categorias,id_categoria'],
            'nombre_examen' => [
                'required','string',
                Rule::unique('examenes','nombre_examen')->ignore($examen->id_examen, 'id_examen'),
            ],
        ]);

        $examen->categoria_id  = $request->categoria_id;
        $examen->nombre_examen = trim($request->nombre_examen);
        $examen->save();

        return redirect()->route('admin.gestionar.examenes')
            ->with('success', 'Examen actualizado correctamente.');
    }

    // Opcional: bloqueo de borrado si está en uso
    public function destroy($id)
    {
        $examen = Examen::withCount('examenesMedicos')->findOrFail($id);
        if ($examen->examenes_medicos_count > 0) {
            return back()->with('error', 'No se puede eliminar: el examen está en uso en expedientes.');
        }
        $examen->delete();

        return back()->with('success', 'Examen eliminado correctamente.');
    }

    /**
     * Crear nueva categoría desde AJAX
     */
    public function storeCategoria(Request $request)
    {
        $request->validate([
            'nombre_categoria' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categorias', 'nombre_categoria')
            ]
        ], [
            'nombre_categoria.required' => 'El nombre de la categoría es obligatorio.',
            'nombre_categoria.unique' => 'Esta categoría ya existe.'
        ]);

        try {
            $categoria = Categoria::create([
                'nombre_categoria' => trim($request->nombre_categoria)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Categoría creada exitosamente.',
                'categoria' => [
                    'id' => $categoria->id_categoria,
                    'nombre' => $categoria->nombre_categoria
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear la categoría: ' . $e->getMessage()
            ], 500);
        }
    }
}
