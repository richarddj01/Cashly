<?php

namespace App\Http\Controllers;

use App\Models\AreaNegocio;
use Illuminate\Http\Request;

class AreaNegocioController extends Controller
{
    public function index()
    {
        $areas = AreaNegocio::where('user_id', auth()->id())
            ->orderBy('nombre')
            ->get();

        return view('areas-negocio.index', compact('areas'));
    }

    public function create()
    {
        return view('areas-negocio.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'color'  => 'required|string|max:7',
            'icono'  => 'nullable|string|max:50',
        ]);

        AreaNegocio::create([
            'user_id' => auth()->id(),
            'nombre'  => $request->nombre,
            'color'   => $request->color,
            'icono'   => $request->icono,
            'activa'  => true,
        ]);

        return redirect()->route('areas-negocio.index')
            ->with('success', 'Área creada correctamente.');
    }

    public function edit(AreaNegocio $areasNegocio)
    {
        abort_if($areasNegocio->user_id !== auth()->id(), 403);
        return view('areas-negocio.edit', compact('areasNegocio'));
    }

    public function update(Request $request, AreaNegocio $areasNegocio)
    {
        abort_if($areasNegocio->user_id !== auth()->id(), 403);

        $request->validate([
            'nombre' => 'required|string|max:255',
            'color'  => 'required|string|max:7',
            'icono'  => 'nullable|string|max:50',
        ]);

        $areasNegocio->update($request->only('nombre', 'color', 'icono', 'activa'));

        return redirect()->route('areas-negocio.index')
            ->with('success', 'Área actualizada correctamente.');
    }

    public function destroy(AreaNegocio $areasNegocio)
    {
        abort_if($areasNegocio->user_id !== auth()->id(), 403);
        $areasNegocio->update(['activa' => false]);

        return redirect()->route('areas-negocio.index')
            ->with('success', 'Área desactivada correctamente.');
    }
}
