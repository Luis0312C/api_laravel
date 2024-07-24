<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MisTickets extends Model
{
    use HasFactory;

    protected $table = 'empleado';
    protected $primaryKey = 'id_empleado';

    public static function getTicketsGenerados($id_empleado, $limite = "")
    {
        $query = DB::table('ticket as t')
            ->join('cliente as c', 't.id_cliente', '=', 'c.id_cliente')
            ->join('estado_ticket as et', 't.id_estado', '=', 'et.id_estado')
            ->join('empleado as e', 't.id_destinatario', '=', 'e.id_empleado')
            ->join('departamento as d', 'e.id_departamento', '=', 'd.id_departamento')
            ->where('t.id_emisor', $id_empleado)
            ->select(
                't.id_ticket',
                't.fecha_creacion',
                't.asunto',
                't.prioridad',
                't.fecha_inicio',
                't.fecha_final',
                't.id_estado',
                'c.dominio as cliente',
                'et.descripcion as estado',
                'd.nombre as departamento',
                DB::raw("CONCAT(e.nombre, ' ', e.ap_paterno, ' ', e.ap_materno) as involucrado")
            )
            ->orderBy('t.id_estado', 'ASC');

        if ($limite) {
            $query->limit($limite);
        }

        return $query->get();
    }

    public static function getTicketsAsociados($id_empleado, $limite = 100)
    {
        $query = DB::table('ticket as t')
            ->join('empleado as e', 't.id_destinatario', '=', 'e.id_empleado')
            ->join('cliente as c', 't.id_cliente', '=', 'c.id_cliente')
            ->join('estado_ticket as et', 't.id_estado', '=', 'et.id_estado')
            ->join('departamento as d', 'e.id_departamento', '=', 'd.id_departamento')
            ->where('t.id_destinatario', $id_empleado)
            ->select(
                't.id_ticket',
                DB::raw('DATE(t.fecha_creacion) AS fecha_creacion'),
                DB::raw('TIME(t.fecha_creacion) AS hora_creacion'),
                't.asunto',
                't.fecha_inicio',
                't.fecha_final',
                't.prioridad',
                't.id_estado',
                'c.dominio AS cliente',
                'et.descripcion AS estado',
                't.id_emisor',
                DB::raw('(SELECT CONCAT(nombre, " ", ap_paterno) FROM empleado WHERE id_empleado = t.id_emisor) AS emisor')
            )
            ->orderBy('t.id_estado', 'ASC');
    
            if ($limite !== 'todo') {
                $limite = (int) $limite;
                if ($limite > 0) {
                    $query->limit($limite);
                }
            }
        return $query->get();
    }
    
}
