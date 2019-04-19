<?php

namespace Modules\Informes\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Informes\Entities\Informe;
use Modules\Informes\Repositories\InformeRepository;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use Carbon\Carbon;
use Modules\Ventas\Entities\Venta;
use DB;
use Log;
use Illuminate\Support\Collection;
use App\Exports\InformeAnualExport;
use Excel;
class InformeController extends AdminBaseController
{

    public function __construct()
    {
        parent::__construct();

    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    private $meses = [
      'Enero',
      'Febrero',
      'Marzo',
      'Abril',
      'Mayo',
      'Junio',
      'Julio',
      'Agosto',
      'Septiembre',
      'Octubre',
      'Noviembre',
      'Diciembre'
    ];
    public function index(Request $request){
        $mes_actual = Carbon::now()->month;
        $meses = array_slice($this->meses, 0, $mes_actual);
        $totales_mes = [];
        foreach ($meses as $key => $mes) {
          array_push($totales_mes, Venta::
              whereMonth('created_at', $key + 1)
            ->whereYear('created_at', Carbon::now()->year)
            ->where('anulado', '0')
            ->get()
            ->sum('monto_total')
          );
        }
        $r = $this->getPerDay($mes_actual, Carbon::now()->year);
        $dias = [];
        $total_dias = array_fill(1, Carbon::now()->daysInMonth, 0);
        $dias = array_keys($total_dias);
        $valores_dias = array_column($r, 'total_dia', 'dia');
        $total_dias = array_values(array_replace_recursive($total_dias, $valores_dias));
        return view('informes::admin.informes.index', compact('meses', 'totales_mes', 'mes_actual', 'dias', 'total_dias'));
    }

    public function getPerDayAjax(Request $request){
      $date = Carbon::now();
      $year = $date->year;
      $r = $this->getPerDay($request->mes + 1, $year);
      $date->month = $request->mes + 1;
      $dias = [];
      $total_dias = array_fill(1, $date->daysInMonth, 0);
      $dias = array_keys($total_dias);
      $valores_dias = array_column($r, 'total_dia', 'dia');
      $total_dias = array_values(array_replace_recursive($total_dias, $valores_dias));
      return response()->json(['error' => false, 'dias' => $dias, 'total_dias' => $total_dias, 'mes' => $this->meses[$request->mes]]);
    }

    private function getPerDay($mes, $year){
      $query = "SELECT SUM(monto_total) AS total_dia, day, substr(day, 1, 2) as dia
        FROM (SELECT *, DATE_FORMAT(created_at, '%d-%m-%Y') AS day FROM ventas__ventas) as T
        WHERE MONTH(created_at) = ".$mes."
        AND YEAR (created_at)  = ".$year."
        AND anulado = 0
        GROUP BY day order by day
        ";

      return DB::select($query);
    }

    public function exportAnual(Request $request){
      return Excel::download(new InformeAnualExport, 'InformeAnual.xlsx');
    }

}
