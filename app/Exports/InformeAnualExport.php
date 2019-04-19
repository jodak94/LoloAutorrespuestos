<?php

namespace App\Exports;

use Modules\Productos\Entities\Producto;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DB;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use Modules\Ventas\Entities\Venta;
class InformeAnualExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
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

    public function collection()
    {
      $mes_actual = Carbon::now()->month;
      $meses = array_slice($this->meses, 0, $mes_actual);
      $totales_mes = new Collection;
      foreach ($meses as $key => $mes) {
        $total = Venta::
            whereMonth('created_at', $key + 1)
          ->whereYear('created_at', Carbon::now()->year)
          ->where('anulado', '0')
          ->get()
          ->sum('monto_total');
        $totales_mes->push(['mes' => $mes, 'total' => number_format($total, 0, ',', '.') . ' Gs.']);
      }
      return $totales_mes;
    }

    public function headings(): array{
        return [
            'Mes',
            'Monto Total',
        ];
    }
}
