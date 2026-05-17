<?php

namespace App\Http\Controllers;

use App\Models\Cuenta;
use Illuminate\Http\Request;

class CuentaController extends Controller
{
    public function index()
    {
        $cuentas = Cuenta::where('user_id', auth()->id())
            ->orderBy('contexto')
            ->orderBy('nombre')
            ->get();

        return view('cuentas.index', compact('cuentas'));
    }

    public function create()
    {
        return view('cuentas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'        => 'required|string|max:255',
            'tipo'          => 'required|in:efectivo,banco,digital',
            'contexto'      => 'required|in:personal,negocio',
            'saldo_inicial' => 'required|numeric|min:0',
        ]);

        Cuenta::create([
            'user_id'       => auth()->id(),
            'nombre'        => $request->nombre,
            'tipo'          => $request->tipo,
            'contexto'      => $request->contexto,
            'saldo_inicial' => $request->saldo_inicial,
            'activa'        => true,
        ]);

        return redirect()->route('cuentas.index')
            ->with('success', 'Cuenta creada correctamente.');
    }

    public function edit(Cuenta $cuenta)
    {
        abort_if($cuenta->user_id !== auth()->id(), 403);
        return view('cuentas.edit', compact('cuenta'));
    }

    public function update(Request $request, Cuenta $cuenta)
    {
        abort_if($cuenta->user_id !== auth()->id(), 403);

        $request->validate([
            'nombre'        => 'required|string|max:255',
            'tipo'          => 'required|in:efectivo,banco,digital',
            'contexto'      => 'required|in:personal,negocio',
            'saldo_inicial' => 'required|numeric|min:0',
        ]);

        $cuenta->update($request->only('nombre', 'tipo', 'contexto', 'saldo_inicial'));

        return redirect()->route('cuentas.index')
            ->with('success', 'Cuenta actualizada correctamente.');
    }

    public function destroy(Cuenta $cuenta)
    {
        abort_if($cuenta->user_id !== auth()->id(), 403);
        $cuenta->update(['activa' => false]);

        return redirect()->route('cuentas.index')
            ->with('success', 'Cuenta desactivada correctamente.');
    }
}
