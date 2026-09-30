<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function clientesPorZona()
    {
        // 1. Consulta con Query Builder
        $zonas = DB::table('clients') // ← HUECO A
        ->select(
            'zona_geografica', // ← HUECO B
            DB::raw('COUNT(*) as total') // ← HUECO C
        )
        ->groupBy('zona_geografica') // ← HUECO D
        ->orderByDesc('total') // ← HUECO E
        ->get(); // ← HUECO F
        // 2. Total general
        $totalGeneral = $zonas->sum('total'); // ← HUECO G
        // 3. Agregar porcentaje
        $zonasConPorcentaje = $zonas->map(function ($zona) use ($totalGeneral) {
        // ← HUECO H
        $zona->porcentaje = $totalGeneral > 0 ? round(($zona->total/ $totalGeneral) * 100, 2) // ← HUECO I
        : 0;
        return $zona; // ← HUECO J
        });
            // 4. Datos para gráfico
            $labels = $zonasConPorcentaje->pluck('zona_geografica')->toArray(); // ←HUECO K
            $data = $zonasConPorcentaje->pluck('total')->toArray(); // ← HUECO L
            return view('reports.zonas', compact(
            'zonasConPorcentaje', 'totalGeneral', 'labels', 'data'
        ));
    }
}