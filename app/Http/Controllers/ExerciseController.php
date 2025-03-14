<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use Illuminate\Http\Request;

class ExerciseController extends Controller
{
    /**
     * Obtener todos los ejercicios.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
{
    $query = Exercise::query();

    // Filtro por nombre
    if ($request->filled('name')) {
        $query->where('name', 'LIKE', '%' . $request->input('name') . '%');
    }

    // Filtro por dificultad
    if ($request->filled('difficulty')) {
        $query->where('difficulty', $request->input('difficulty'));
    }

    // Filtro por músculo principal
    if ($request->filled('main_muscle')) {
        $query->where('main_muscle', $request->input('main_muscle'));
    }

    // Filtro por equipo
    if ($request->filled('equipment')) {
        $query->where('equipment', $request->input('equipment'));
    }

    $exercises = $query->paginate(10);

    return response()->json([
        'success' => true,
        'data' => $exercises
    ]);
}

   

    /**
     * Crear un nuevo ejercicio.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'difficulty' => 'required|string',
            'main_muscle' => 'required|string',
            'equipment' => 'required|string',
            'image' => 'required|url',
        ]);

        $exercise = Exercise::create($validatedData);

        return response()->json($exercise, 201);
    }

    /**
     * Obtener un ejercicio específico.
     *
     * @param  \App\Models\Exercise  $exercise
     * @return \Illuminate\Http\Response
     */
    public function show(Exercise $exercise)
    {
        return response()->json($exercise);
    }

    /**
     * Actualizar un ejercicio existente.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Exercise  $exercise
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Exercise $exercise)
    {
        $validatedData = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'difficulty' => 'sometimes|string',
            'main_muscle' => 'sometimes|string',
            'equipment' => 'sometimes|string',
            'image' => 'sometimes|url',
        ]);

        $exercise->update($validatedData);

        return response()->json($exercise);
    }

    /**
     * Eliminar un ejercicio.
     *
     * @param  \App\Models\Exercise  $exercise
     * @return \Illuminate\Http\Response
     */
    public function destroy(Exercise $exercise)
    {
        $exercise->delete();
        return response()->json(['message' => 'Exercise deleted successfully']);
    }
}
