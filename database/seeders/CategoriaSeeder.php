<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categoria;
use App\Models\User;

class CategoriaSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            // ── Personal — Ingresos ──────────────────
            ['nombre' => 'Salario',          'tipo' => 'ingreso', 'contexto' => 'personal', 'color' => '#198754', 'icono' => 'cash'],
            ['nombre' => 'Freelance',         'tipo' => 'ingreso', 'contexto' => 'personal', 'color' => '#0d6efd', 'icono' => 'laptop'],
            ['nombre' => 'Otros ingresos',    'tipo' => 'ingreso', 'contexto' => 'personal', 'color' => '#4cc9f0', 'icono' => 'plus-circle'],

            // ── Personal — Egresos ───────────────────
            ['nombre' => 'Alimentación',      'tipo' => 'egreso',  'contexto' => 'personal', 'color' => '#fd7e14', 'icono' => 'basket'],
            ['nombre' => 'Transporte',         'tipo' => 'egreso',  'contexto' => 'personal', 'color' => '#0dcaf0', 'icono' => 'car-front'],
            ['nombre' => 'Salud',              'tipo' => 'egreso',  'contexto' => 'personal', 'color' => '#dc3545', 'icono' => 'heart-pulse'],
            ['nombre' => 'Educación',          'tipo' => 'egreso',  'contexto' => 'personal', 'color' => '#6610f2', 'icono' => 'book'],
            ['nombre' => 'Entretenimiento',    'tipo' => 'egreso',  'contexto' => 'personal', 'color' => '#d63384', 'icono' => 'controller'],
            ['nombre' => 'Servicios básicos',  'tipo' => 'egreso',  'contexto' => 'personal', 'color' => '#ffc107', 'icono' => 'lightning'],
            ['nombre' => 'Ropa',               'tipo' => 'egreso',  'contexto' => 'personal', 'color' => '#e83e8c', 'icono' => 'bag'],
            ['nombre' => 'Gastos varios',      'tipo' => 'egreso',  'contexto' => 'personal', 'color' => '#6c757d', 'icono' => 'three-dots'],

            // ── Negocio — Ingresos ───────────────────
            ['nombre' => 'Ventas',             'tipo' => 'ingreso', 'contexto' => 'negocio',  'color' => '#198754', 'icono' => 'shop'],
            ['nombre' => 'Depósitos',          'tipo' => 'ingreso', 'contexto' => 'negocio',  'color' => '#0d6efd', 'icono' => 'bank'],
            ['nombre' => 'Comisiones',         'tipo' => 'ingreso', 'contexto' => 'negocio',  'color' => '#7209b7', 'icono' => 'phone'],

            // ── Negocio — Egresos ────────────────────
            ['nombre' => 'Pago empleados',     'tipo' => 'egreso',  'contexto' => 'negocio',  'color' => '#fd7e14', 'icono' => 'people'],
            ['nombre' => 'Internet',           'tipo' => 'egreso',  'contexto' => 'negocio',  'color' => '#0dcaf0', 'icono' => 'wifi'],
            ['nombre' => 'Energía eléctrica',  'tipo' => 'egreso',  'contexto' => 'negocio',  'color' => '#ffc107', 'icono' => 'lightning-charge'],
            ['nombre' => 'Renta local',        'tipo' => 'egreso',  'contexto' => 'negocio',  'color' => '#dc3545', 'icono' => 'building'],
            ['nombre' => 'Insumos',            'tipo' => 'egreso',  'contexto' => 'negocio',  'color' => '#6610f2', 'icono' => 'box'],
            ['nombre' => 'Gastos operativos',  'tipo' => 'egreso',  'contexto' => 'negocio',  'color' => '#6c757d', 'icono' => 'gear'],

            // ── Ambos ────────────────────────────────
            ['nombre' => 'Impuestos',          'tipo' => 'egreso',  'contexto' => 'ambos',    'color' => '#dc3545', 'icono' => 'receipt'],
        ];

        // Crear para cada usuario existente
        User::all()->each(function ($user) use ($categorias) {
            foreach ($categorias as $cat) {
                // Solo crea si no existe ya esa categoría para ese usuario
                Categoria::firstOrCreate(
                    [
                        'user_id'  => $user->id,
