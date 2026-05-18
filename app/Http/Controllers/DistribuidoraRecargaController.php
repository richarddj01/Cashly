<?php

namespace App\Http\Controllers;

use App\Models\DistribuidoraRecarga;
use Illuminate\Http\Request;

class DistribuidoraRecargaController extends Controller
{
    public function index()
    {
        $distribuidoras = DistribuidoraRecarga::where('user_id', auth()->id())
            ->orderBy('nombre')
            ->get();

        return view('distribuidoras.index', compact('distribuidoras'));
    }

    public function create()
    {
        return view('distribuidoras.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'       => 'required|string|max:255',
            'saldo_actual' => 'required|numeric|min:0',
            'saldo_minimo' => 'required|numeric|min:0',
        ]);

        DistribuidoraRecarga::create([
            'user_id'      => auth()->id(),
            'nombre'       => $request->nombre,
            'saldo_actual' => $request->saldo_actual,
            'saldo_minimo' => $request->saldo_minimo,
            'activa'       => true,
        ]);

        return redirect()->route('distribuidoras.index')
            ->with('success', 'Distribuidora creada correctamente.');
    }

    public function edit(DistribuidoraRecarga $distribuidora)
    {
        abort_if($distribuidora->user_id !== auth()->id(), 403);
        return view('distribuidoras.edit', compact('distribuidora'));
    }

    public function update(Request $request, DistribuidoraRecarga $distribuidora)
    {
        abort_if($distribuidora->user_id !== auth()->id(), 403);

        $request->validate([
            'nombre'       => 'required|string|max:255',
            'saldo_minimo' => 'required|numeric|min:0',
        ]);

        $distribuidora->update($request->only('nombre', 'saldo_minimo', 'activa'));

        return redirect()->route('distribuidoras.index')
            ->with('success', 'Distribuidora actualizada correctamente.');
    }

    public function destroy(DistribuidoraRecarga $distribuidora)
    {
        abort_if($distribuidora->user_id !== auth()->id(), 403);
        $distribuidora->update(['activa' => false]);

        return redirect()->route('distribuidoras.index')
            ->with('success', 'Distribuidora desactivada correctamente.');
    }
}
