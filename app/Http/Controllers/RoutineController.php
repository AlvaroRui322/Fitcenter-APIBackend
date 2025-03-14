<?php

namespace App\Http\Controllers;

use App\Models\Routine;
use App\Models\Exercise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoutineController extends Controller
{
    // ... métodos existentes ...

    public function index()
    {
        // Obtener rutinas del usuario autenticado
        return Routine::where('user_id', Auth::id())->get();
    }

    public function addExercise(Request $request, Routine $routine)
    {
        // Verificar propiedad de la rutina
        if ($routine->user_id !== Auth::id()) {
            return response()->json(['error' => 'No tienes permiso para modificar esta rutina'], 403);
        }

        // Validar datos
        $validated = $request->validate([
            'exercise_id' => 'required|exists:exercises,id',
            'series' => 'required|integer|min:1',
            'repetitions' => 'required|integer|min:1'
        ]);

        // Añadir ejercicio a la rutina
        $routine->exercises()->attach($validated['exercise_id'], [
            'series' => $validated['series'],
            'repetitions' => $validated['repetitions']
        ]);

        return response()->json(['message' => 'Ejercicio añadido correctamente']);
    }

    public function store(Request $request)
{
    // Validación
    $data = $request->validate([
        'name' => 'required|string|max:255'
    ]);

    // Crear rutina con user_id
    $routine = Routine::create([
        'name' => $data['name'],
        'user_id' => Auth::id()
    ]);

    return response()->json($routine, 201);
}

public function getExercises(Routine $routine)
{
    if($routine->user_id !== Auth::id()) {
        return response()->json(['error' => 'No tienes permiso'], 403);
    }

    $exercises = $routine->exercises()->withPivot('series', 'repetitions')->get();

    return response()->json($exercises);
}

public function destroy(Routine $routine)
{
    // Verificar autenticación y propiedad
    if (!Auth::check()) {
        return response()->json(['error' => 'No autenticado'], 401);
    }

    if ($routine->user_id !== Auth::id()) {
        return response()->json(['error' => 'No tienes permiso'], 403);
    }

    // Eliminar la rutina y sus relaciones
    $routine->exercises()->detach();
    $routine->delete();

    return response()->json(['message' => 'Rutina eliminada']);
}

public function removeExercise(Routine $routine, Exercise $exercise)
{
    if ($routine->user_id !== Auth::id()) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    $routine->exercises()->detach($exercise->id);
    return response()->json(['message' => 'Ejercicio eliminado']);
}


}
