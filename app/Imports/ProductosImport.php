<?php

namespace App\Imports;

use Modules\Productos\Entities\Producto;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductosImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Producto([
            'codigo' => $row['codigo'],
            'nombre'=> $row['nombre'],
            'stock'=> $row['stock'],
            'stock_critico'=> $row['stock_critico'],
            'precio'=> $row['precio'],
            'costo'=> $row['costo'],
            'descuento'=> $row['descuento']

        ]);
    }

}
