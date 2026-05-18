<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    public function index()
    {
        $categorias = Categoria::where('user_id', auth()->id())
            ->orderBy('contexto')
            ->orderBy('tipo')
            ->orderBy('nombre')
            ->get();

        return view('categorias.index', compact('categorias'));
    }

    public function create()
    {
        return view('categorias.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'   => 'required|string|max:255',
            'tipo'     => 'required|in:ingreso,egreso',
            'contexto' => 'required|in:personal,negocio,ambos',
            'color'    => 'required|string|max:7',
            'icono'    => 'nullable|string|max:50',
        ]);

        Categoria::create([
            'user_id'  => auth()->id(),
            'nombre'   => $request->nombre,
            'tipo'     => $request->tipo,
            'contexto' => $request->contexto,
            'color'    => $request->color,
            'icono'    => $request->icono,
        ]);

        return redirect()->route('categorias.index')
            ->with('success', 'Categoría creada correctamente.');
    }

    public function edit(Categoria $categoria)
    {
        abort_if($categoria->user_id !== auth()->id(), 403);
        return view('categorias.edit', compact('categoria'));
    }

    public function update(Request $request, Categoria $categoria)
    {
        abort_if($categoria->user_id !== auth()->id(), 403);

        $request->validate([
            'nombre'   => 'required|string|max:255',
            'tipo'     => 'required|in:ingreso,egreso',
            'contexto' => 'required|in:negocio,impresiones,recargas,personal',
            'color'    => 'required|string|max:7',
            'icono'    => 'nullable|string|max:50',
        ]);

        $categoria->update($request->only('nombre', 'tipo', 'contexto', 'color', 'icono'));

        return redirect()->route('categorias.index')
            ->with('success', 'Categoría actualizada correctamente.');
    }

    public function destroy(Categoria $categoria)
    {
        abort_if($categoria->user_id !== auth()->id(), 403);
        $categoria->delete();

        return redirect()->route('categorias.index')
            ->with('success', 'Categoría eliminada correctamente.');
    }
}
