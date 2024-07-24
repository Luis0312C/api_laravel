<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Prospecto extends Model
{
    public function get_prospectos_por_empleado($id_empleado, $limite = '')
    {
        $query = "SELECT 
                    p.*, ep.descripcion as nombre_estatus
                  FROM 
                    prospecto p
                  INNER JOIN 
                    op_estatus_prospecto ep ON p.id_estatus = ep.id_estatus
                  WHERE 
                    p.id_empleado = ?
                  " . $limite;

        return DB::select($query, [$id_empleado]);
    }

    

    public function get_prospectos($id = 0)
    {
        if ($id == 0) {
            return DB::select(
                'SELECT  
                    p.id_prospecto,
                    p.nombre_empresa,
                    p.dominio,
                    p.fecha_contacto,
                    p.nombre_contacto,
                    p.id_empleado,
                    p.fuente,
                    p.id_estatus,
                    CONCAT(e.nombre, " ", e.ap_paterno, " ", e.ap_materno) AS nombre,
                    ep.descripcion
                FROM 
                    prospecto p
                JOIN 
                    empleado e ON p.id_empleado = e.id_empleado
                JOIN 
                    op_estatus_prospecto ep ON p.id_estatus = ep.id_estatus
                ORDER BY 
                    p.id_prospecto ASC  LIMIT 100
                    '
            );
        } else {
            return DB::select(
                'SELECT  
                    p.id_prospecto,
                    p.nombre_empresa,
                    p.dominio,
                    p.fecha_contacto,
                    p.nombre_contacto,
                    p.id_empleado,
                    p.fuente,
                    p.id_estatus,
                    CONCAT(e.nombre, " ", e.ap_paterno, " ", e.ap_materno) AS nombre,
                    ep.descripcion
                FROM 
                    prospecto p
                JOIN 
                    empleado e ON p.id_empleado = e.id_empleado
                JOIN 
                    op_estatus_prospecto ep ON p.id_estatus = ep.id_estatus
                WHERE 
                    p.id_prospecto = ?', [$id]
                    
            );
        }
    }
}