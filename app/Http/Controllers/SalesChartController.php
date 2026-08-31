<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;

class SalesChartController extends Controller
{
    public function salesData()
    {
        $byYear = Sale::select(
            DB::raw('YEAR(created_at) as year'),
            DB::raw('SUM(total) as total')
        )
        ->groupBy('year')
        ->orderBy('year', 'asc')
        ->get();

        $byMonth = Sale::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('SUM(total) as total')
        )
        ->whereYear('created_at', date('Y'))
        ->groupBy('month')
        ->orderBy('month', 'asc')
        ->get();

        $monthsMap = [
            '1' => 'Ene', '2' => 'Feb', '3' => 'Mar', '4' => 'Abr', 
            '5' => 'May', '6' => 'Jun', '7' => 'Jul', '8' => 'Ago', 
            '9' => 'Sep', '10' => 'Oct', '11' => 'Nov', '12' => 'Dic'
        ];
        
        $monthLabels = [];
        $monthData = [];
        foreach ($byMonth as $row) {
            $monthLabels[] = $monthsMap[$row->month] ?? $row->month;
            $monthData[] = (float) $row->total;
        }

        $byDay = Sale::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(total) as total')
        )
        ->where('created_at', '>=', now()->subDays(7))
        ->groupBy('date')
        ->orderBy('date', 'asc')
        ->get();

        return response()->json([
            'year' => [
                'labels' => $byYear->pluck('year')->toArray(),
                'data' => $byYear->pluck('total')->map(fn($val) => (float)$val)->toArray(),
            ],
            'month' => [
                'labels' => $monthLabels,
                'data' => $monthData,
            ],
            'day' => [
                'labels' => $byDay->pluck('date')->toArray(),
                'data' => $byDay->pluck('total')->map(fn($val) => (float)$val)->toArray(),
            ],
        ]);
    }
}