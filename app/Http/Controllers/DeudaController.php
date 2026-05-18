<?php

namespace App\Http\Controllers;

use App\Models\Deuda;
use Illuminate\Http\Request;

class DeudaController extends Controller
{
    public function index()
    {
        $deudas = Deuda::where('user_id', auth()->id())
            ->orderBy('estado')
            ->orderBy('fecha_vencimiento')
            ->paginate(20);

        $totalPendiente = Deuda::where('user_id', auth()->id())
            ->where('estado', 'activa')
            ->selectRaw('SUM(monto_total - monto_pagado) as total')
            ->value('total') ?? 0;

        return view('deudas.index', compact('deudas', 'totalPendiente'));
    }

    public function create()
    {
        return view('deudas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'acreedor'          => 'required|string|max:255',
            'monto_total'       => 'required|numeric|min:0.01',
            'tipo'              => 'required|in:prestamo,tarjeta,cuota,otro',
            'fecha_inicio'      => 'required|date',
            'fecha_vencimiento' => 'nullable|date',
            'interes'           => 'nullable|numeric|min:0',
        ]);

        Deuda::create([
            'user_id'           => auth()->id(),
            'acreedor'          => $request->acreedor,
            'monto_total'       => $request->monto_total,
            'monto_pagado'      => 0,
            'interes'           => $request->interes ?? 0,
            'tipo'              => $request->tipo,
            'fecha_inicio'      => $request->fecha_inicio,
            'fecha_vencimiento' => $request->fecha_vencimiento,
            'estado'            => 'activa',
            'notas'             => $request->notas,
        ]);

        return redirect()->route('deudas.index')
            ->with('success', 'Deuda registrada correctamente.');
    }

    public function edit(Deuda $deuda)
    {
        abort_if($deuda->user_id !== auth()->id(), 403);
        return view('deudas.edit', compact('deuda'));
    }

    public function update(Request $request, Deuda $deuda)
    {
        abort_if($deuda->user_id !== auth()->id(), 403);

        $request->validate([
            'monto_pagado' => 'required|numeric|min:0',
            'estado'       => 'required|in:activa,pagada,vencida',
        ]);

        $deuda->update($request->only('monto_pagado', 'estado', 'notas'));

        return redirect()->route('deudas.index')
            ->with('success', 'Deuda actualizada correctamente.');
    }

    public function destroy(Deuda $deuda)
    {
        abort_if($deuda->user_id !== auth()->id(), 403);
        $deuda->delete();

        return redirect()->route('deudas.index')
            ->with('success', 'Deuda eliminada.');
    }
}
