<?php

namespace App\Http\Controllers;

use App\Models\MetaAhorro;
use App\Models\Cuenta;
use Illuminate\Http\Request;

class MetaAhorroController extends Controller
{
    public function index()
    {
        $metas = MetaAhorro::where('user_id', auth()->id())
            ->with('cuenta')
            ->orderBy('estado')
            ->orderBy('fecha_limite')
            ->get();

        return view('metas-ahorro.index', compact('metas'));
    }

    public function create()
    {
        $cuentas = Cuenta::where('user_id', auth()->id())
            ->where('activa', true)
            ->where('contexto', 'personal')
            ->get();

        return view('metas-ahorro.create', compact('cuentas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'          => 'required|string|max:255',
            'cuenta_id'       => 'required|exists:cuentas,id',
            'monto_objetivo'  => 'required|numeric|min:0.01',
            'monto_actual'    => 'nullable|numeric|min:0',
            'fecha_limite'    => 'nullable|date',
        ]);

        MetaAhorro::create([
            'user_id'        => auth()->id(),
            'cuenta_id'      => $request->cuenta_id,
            'nombre'         => $request->nombre,
            'monto_objetivo' => $request->monto_objetivo,
            'monto_actual'   => $request->monto_actual ?? 0,
            'fecha_limite'   => $request->fecha_limite,
            'estado'         => 'activa',
            'notas'          => $request->notas,
        ]);

        return redirect()->route('metas-ahorro.index')
            ->with('success', 'Meta de ahorro creada correctamente.');
    }

    public function edit(MetaAhorro $metasAhorro)
    {
        abort_if($metasAhorro->user_id !== auth()->id(), 403);

        $cuentas = Cuenta::where('user_id', auth()->id())
            ->where('activa', true)->get();

        return view('metas-ahorro.edit', compact('metasAhorro', 'cuentas'));
    }

    public function update(Request $request, MetaAhorro $metasAhorro)
    {
        abort_if($metasAhorro->user_id !== auth()->id(), 403);

        $request->validate([
            'monto_actual' => 'required|numeric|min:0',
            'estado'       => 'required|in:activa,completada,cancelada',
        ]);

        $metasAhorro->update($request->only('monto_actual', 'estado', 'notas'));

        return redirect()->route('metas-ahorro.index')
            ->with('success', 'Meta actualizada correctamente.');
    }

    public function destroy(MetaAhorro $metasAhorro)
    {
        abort_if($metasAhorro->user_id !== auth()->id(), 403);
        $metasAhorro->delete();

        return redirect()->route('metas-ahorro.index')
            ->with('success', 'Meta eliminada.');
    }
}
