<?php

namespace App\Http\Controllers;

use App\Models\Empleado;
use Illuminate\Http\Request;

class EmpleadoController extends Controller
{
    public function index()
    {
        $empleados = Empleado::where('user_id', auth()->id())
            ->orderBy('nombre')
            ->get();

        return view('empleados.index', compact('empleados'));
    }

    public function create()
    {
        return view('empleados.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'        => 'required|string|max:255',
            'cargo'         => 'nullable|string|max:255',
            'salario'       => 'required|numeric|min:0',
            'fecha_ingreso' => 'required|date',
        ]);

        Empleado::create([
            'user_id'       => auth()->id(),
            'nombre'        => $request->nombre,
            'cargo'         => $request->cargo,
            'salario'       => $request->salario,
            'fecha_ingreso' => $request->fecha_ingreso,
            'activo'        => true,
        ]);

        return redirect()->route('empleados.index')
            ->with('success', 'Empleado registrado correctamente.');
    }

    public function edit(Empleado $empleado)
    {
        abort_if($empleado->user_id !== auth()->id(), 403);
        return view('empleados.edit', compact('empleado'));
    }

    public function update(Request $request, Empleado $empleado)
    {
        abort_if($empleado->user_id !== auth()->id(), 403);

        $request->validate([
            'nombre'        => 'required|string|max:255',
            'cargo'         => 'nullable|string|max:255',
            'salario'       => 'required|numeric|min:0',
            'fecha_ingreso' => 'required|date',
        ]);

        $empleado->update($request->only('nombre', 'cargo', 'salario', 'fecha_ingreso', 'activo'));

        return redirect()->route('empleados.index')
            ->with('success', 'Empleado actualizado correctamente.');
    }

    public function destroy(Empleado $empleado)
    {
        abort_if($empleado->user_id !== auth()->id(), 403);
        $empleado->update(['activo' => false]);

        return redirect()->route('empleados.index')
            ->with('success', 'Empleado desactivado correctamente.');
    }
}
