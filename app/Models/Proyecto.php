<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Proyecto extends Model
{
    use HasFactory;
    protected $table = 'cliente';

    public function get_top_nuevos($idEmpleado)
    {
        return DB::select(
            'SELECT id_cliente, dominio 
             FROM cliente 
             WHERE id_cliente IN (
                SELECT id_cliente 
                FROM integrante_proyecto 
                WHERE id_empleado = ?) 
             AND activo 
             ORDER BY fecha_ingreso DESC 
             LIMIT 5',
            [$idEmpleado]
        );
    }
}
