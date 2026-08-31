<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;
use Exception;

class SalesChartController extends Controller
{
    public function salesData()
    {
        try {
            // 1. Datos por Año
            $byYear = Sale::select(
                DB::raw('YEAR(created_at) as label'),
                DB::raw('SUM(total) as total'),
                DB::raw('COUNT(id) as count')
            )->groupBy(DB::raw('YEAR(created_at)'))->get();

            // 2. Datos por Mes (Año Actual)
            $byMonth = Sale::select(
                DB::raw('MONTH(created_at) as month_num'),
                DB::raw('SUM(total) as total'),
                DB::raw('COUNT(id) as count')
            )
            ->whereYear('created_at', date('Y'))
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->get();

            $monthsMap = [1=>'Ene', 2=>'Feb', 3=>'Mar', 4=>'Abr', 5=>'May', 6=>'Jun', 7=>'Jul', 8=>'Ago', 9=>'Sep', 10=>'Oct', 11=>'Nov', 12=>'Dic'];
            $monthLabels = []; $monthTotals = []; $monthCounts = [];
            foreach ($byMonth as $row) {
                $monthLabels[] = $monthsMap[$row->month_num] ?? 'Mes ' . $row->month_num;
                $monthTotals[] = (float) $row->total;
                $monthCounts[] = (int) $row->count;
            }

            // 3. Datos por Día (Últimos 7 días)
            $byDay = Sale::select(
                DB::raw('DATE_FORMAT(created_at, "%d/%m") as label'),
                DB::raw('SUM(total) as total'),
                DB::raw('COUNT(id) as count')
            )
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy(DB::raw('DATE_FORMAT(created_at, "%d/%m")'))
            ->get();

            // 4. Datos por Día de la Semana (Radar)
            $radarValues = [0, 0, 0, 0, 0, 0, 0];
            $byDayOfWeek = Sale::select(
                DB::raw('DAYOFWEEK(created_at) as day_idx'),
                DB::raw('SUM(total) as total')
            )->groupBy(DB::raw('DAYOFWEEK(created_at)'))->get();

            $mapIndexes = [2=>0, 3=>1, 4=>2, 5=>3, 6=>4, 7=>5, 1=>6]; // Lun a Dom
            foreach ($byDayOfWeek as $row) {
                if (isset($mapIndexes[$row->day_idx])) {
                    $radarValues[$mapIndexes[$row->day_idx]] = (float) $row->total;
                }
            }

            // 5. Proporción/Estado (Dona)
            $donutLabels = ['Ventas Realizadas'];
            $donutData = [(float) Sale::sum('total')];

            return response()->json([
                'year' => [
                    'labels' => $byYear->pluck('label')->map(fn($v) => (string)$v)->toArray(),
                    'totals' => $byYear->pluck('total')->map(fn($v) => (float)$v)->toArray(),
                    'counts' => $byYear->pluck('count')->map(fn($v) => (int)$v)->toArray(),
                ],
                'month' => [
                    'labels' => empty($monthLabels) ? ['Sin registros'] : $monthLabels,
                    'totals' => empty($monthTotals) ? [0] : $monthTotals,
                    'counts' => empty($monthCounts) ? [0] : $monthCounts,
                ],
                'day' => [
                    'labels' => $byDay->pluck('label')->toArray(),
                    'totals' => $byDay->pluck('total')->map(fn($v) => (float)$v)->toArray(),
                    'counts' => $byDay->pluck('count')->map(fn($v) => (int)$v)->toArray(),
                ],
                'radar' => [
                    'labels' => ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'],
                    'data' => $radarValues
                ],
                'donut' => [
                    'labels' => $donutLabels,
                    'data' => $donutData
                ]
            ]);

        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}