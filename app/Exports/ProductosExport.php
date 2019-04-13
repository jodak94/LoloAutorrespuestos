<?php

namespace App\Exports;

use Modules\Productos\Entities\Producto;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use DB;
class ProductosExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return DB::table('productos__productos')
                 ->select('codigo', 'nombre','stock', 'costo', 'precio')
                 ->get();
    }

    public function headings(): array{
        return [
            'Código',
            'Nombre',
            'Stock',
            'Costo',
            'Precio'
        ];
    }
}
