<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Empleado extends Model
{
    protected $table = 'empleado';
    protected $primaryKey = 'id_empleado';


    public function getEmpleados($id = 0, $status = 1, $departamento = '%', $region = '%', $orderby = 1)
    {
        if ($id == 0) {
            return DB::select("
                SELECT 
                    e.id_empleado,
                    e.nombre,
                    e.ap_paterno,
                    e.ap_materno,
                    e.fecha_nac,
                    e.genero,
                    e.fecha_ingreso,
                    e.telefono,
                    e.email_empresa,
                    e.usuario_skype,
                    e.id_departamento,
                    d.nombre as departamento,
                    r.id_region,        
                    r.nombre as nombre_region,
                    e.usuario_activo,
                    u.mi_agenda,
                    u.id_user
                FROM empleado e
                JOIN op_user u ON e.id_empleado = u.id_empleado
                JOIN departamento d ON e.id_departamento = d.id_departamento
                JOIN region r ON e.id_region = r.id_region
                WHERE e.activo = ?
                AND e.id_departamento LIKE ?
                AND e.id_region LIKE ?
                ORDER BY ?
            ", [$status, $departamento, $region, $orderby]);
        } else {
            return DB::select("
                SELECT
                    empleado.id_empleado, 
                    empleado.nombre,
                    empleado.ap_paterno,
                    empleado.ap_materno,
                    empleado.genero,
                    empleado.fecha_ingreso,
                    empleado.fecha_nac,
                    empleado.telefono,
                    empleado.email_empresa,
                    empleado.usuario_skype,
                    empleado.id_region,
                    empleado.id_departamento,
                    departamento.nombre AS depto
                FROM empleado
                JOIN departamento ON empleado.id_departamento = departamento.id_departamento
                WHERE empleado.id_empleado = ?
                AND empleado.activo
                ORDER BY empleado.fecha_ingreso
            ", [$id]);
        }
    }


    public function get_totales($id_empleado){

        $query = "SELECT CONCAT(nombre, ' ', ap_paterno) AS nombre, 
                  (SELECT count(id_cliente) FROM cliente WHERE activo AND id_cliente IN
                      (SELECT id_cliente FROM integrante_proyecto WHERE id_empleado = ?)) AS proyectos_activos,
                  (SELECT count(id_cliente) FROM cliente WHERE !activo AND id_cliente IN
                      (SELECT id_cliente FROM integrante_proyecto WHERE id_empleado = ?)) AS proyectos_inactivos,
                  (SELECT COUNT(id_prospecto) FROM prospecto WHERE id_empleado = ?) AS prospectos,
                  (SELECT COUNT(id_ticket) FROM ticket WHERE id_destinatario = ?) AS tickets_asignados,
                  (SELECT COUNT(id_ticket) FROM ticket WHERE id_destinatario = ? AND id_estado != 3) AS tickets_pendientes,
                  (SELECT COUNT(id_ticket) FROM ticket WHERE id_destinatario = ? AND id_estado = 3) AS tickets_resueltos,
                  (SELECT COUNT(id_ticket) FROM ticket WHERE id_emisor = ?) AS tickets_generados
                FROM empleado
                WHERE id_empleado = ?";
                
                return DB::select($query, [$id_empleado, $id_empleado, $id_empleado, $id_empleado, $id_empleado, $id_empleado, $id_empleado, $id_empleado]);

    }  
    
    public function get_ultimos_tickets_asociados($idEmpleado)
    {
        return DB::select(
            'SELECT 
                t.id_ticket,
                et.id_estado,
                CONCAT(SUBSTR(t.asunto, 1, 50), "...") AS asunto,
                c.dominio AS cliente,
                DATE(t.fecha_creacion) AS fecha_creacion,
                TIME(t.fecha_creacion) AS hora_creacion,
                t.prioridad,
                et.descripcion AS status,
                t.id_emisor,
                u.id_user,
                e.nombre AS emisor,
                e.email_empresa
            FROM 
                ticket t
            JOIN 
                estado_ticket et ON t.id_estado = et.id_estado
            JOIN 
                cliente c ON c.id_cliente = t.id_cliente
            JOIN 
                empleado e ON t.id_emisor = e.id_empleado
            JOIN 
                op_user u ON t.id_emisor = u.id_empleado
            WHERE 
                t.id_destinatario = ? AND 
                et.id_estado != 3
            ORDER BY 
                t.id_ticket DESC 
            LIMIT 5',
            [$idEmpleado]
        );
    }

    public function get_ultimos_tickets_generados($idEmpleado)
    {
        return DB::select(
            'SELECT 
                t.id_ticket,
                t.asunto,
                t.prioridad,
                c.dominio AS cliente,
                t.id_estado,
                t.fecha_creacion,
                et.descripcion AS status,
                CONCAT(e.nombre, " ", e.ap_paterno) AS destinatario,
                e.email_empresa,
                u.id_user
            FROM 
                ticket t
            JOIN 
                cliente c ON c.id_cliente = t.id_cliente
            JOIN 
                estado_ticket et ON t.id_estado = et.id_estado
            JOIN 
                empleado e ON t.id_destinatario = e.id_empleado
            JOIN 
                op_user u ON t.id_destinatario = u.id_empleado
            WHERE 
                t.id_emisor = ?
            ORDER BY 
                t.id_ticket DESC 
            LIMIT 5',
            [$idEmpleado]
        );
    }

    
    public function get_tickets_asociados($id_empleado, $limite)
{
    $limiteQuery = $limite == 'todo' ? '' : 't.id_estado BETWEEN 1 AND 2 AND';

    $query = "SELECT 
                t.id_ticket,
                DATE(t.fecha_creacion) AS fecha_creacion,
                TIME(t.fecha_creacion) AS hora_creacion,							
                t.asunto,
                t.fecha_inicio,
                t.fecha_final,
                t.prioridad,
                t.id_estado,
                c.dominio AS cliente,
                et.descripcion AS estado,
                t.id_emisor,
                (SELECT CONCAT(nombre,' ',ap_paterno) FROM empleado WHERE id_empleado = t.id_emisor) AS emisor
              FROM 
                ticket t, 
                departamento d, 
                empleado e, 
                cliente c,
                estado_ticket et
              WHERE 
                t.id_destinatario = ? AND 
                t.id_destinatario = e.id_empleado AND 
                t.id_cliente = c.id_cliente AND
                t.id_estado = et.id_estado AND 
                $limiteQuery e.id_departamento = d.id_departamento 
              ORDER BY 
                t.id_estado ASC";

    return DB::select($query, [$id_empleado]);
}



public function get_img($idEmpleado) {
    return DB::select(
        'SELECT email_empresa FROM empleado WHERE id_empleado = ?', [$idEmpleado]
    );

}



}