<?php

namespace App\Http\Controllers;

use App\Models\Presupuesto;
use App\Models\Categoria;
use Illuminate\Http\Request;

class PresupuestoController extends Controller
{
    public function index()
    {
        $presupuestos = Presupuesto::where('user_id', auth()->id())
            ->with('categoria')
            ->where('anio', date('Y'))
            ->where('mes', date('n'))
            ->get();

        return view('presupuestos.index', compact('presupuestos'));
    }

    public function create()
    {
        $categorias = Categoria::where('user_id', auth()->id())
            ->where('tipo', 'egreso')
            ->whereIn('contexto', ['personal', 'ambos'])
            ->orderBy('nombre')
            ->get();

        return view('presupuestos.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'categoria_id'  => 'required|exists:categorias,id',
            'monto_limite'  => 'required|numeric|min:0.01',
            'mes'           => 'required|integer|between:1,12',
            'anio'          => 'required|integer|min:2024',
        ]);

        Presupuesto::updateOrCreate(
            [
                'user_id'      => auth()->id(),
                'categoria_id' => $request->categoria_id,
                'mes'          => $request->mes,
                'anio'         => $request->anio,
            ],
            ['monto_limite' => $request->monto_limite]
        );

        return redirect()->route('presupuestos.index')
            ->with('success', 'Presupuesto guardado correctamente.');
    }

    public function destroy(Presupuesto $presupuesto)
    {
        abort_if($presupuesto->user_id !== auth()->id(), 403);
        $presupuesto->delete();

        return redirect()->route('presupuestos.index')
            ->with('success', 'Presupuesto eliminado.');
    }
}
