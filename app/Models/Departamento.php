<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Departamento extends Model
{
    use HasFactory;
    protected $table = 'departamento';
    protected $primaryKey = 'id_departamento';

    public function getDepartamentos($id = 0)
    {
        if ($id == 0) {
            return DB::select("
                SELECT 
                    d.id_departamento AS id, 
                    d.nombre, 
                    COUNT(e.id_departamento) AS total
                FROM departamento d 
                LEFT JOIN empleado e ON d.id_departamento = e.id_departamento AND e.activo
                GROUP BY d.id_departamento, d.nombre
            ");
        } else {
            return DB::select("
                SELECT id_departamento AS id, nombre 
                FROM departamento 
                WHERE id_departamento = ?
            ", [$id]);
        }
    }
}
