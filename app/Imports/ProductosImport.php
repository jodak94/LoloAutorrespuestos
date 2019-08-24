<?php

namespace App\Imports;

use Modules\Productos\Entities\Producto;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ProductosImport implements ToModel, WithHeadingRow, WithMultipleSheets
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
            'descripcion' => $row['descripcion'],
            'precio'=> $row['precio'],
            'costo'=> $row['costo'],
            'descuento'=> $row['descuento']

        ]);
    }

   public function sheets(): array
   {
       return [
           0 => new static
       ];
   }

}
