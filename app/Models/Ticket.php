<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $table = 'ticket';
    protected $primaryKey = 'id_ticket';

    public function get_total_tickets_pendientes()
    {
        return $this->selectRaw('CONCAT(e.nombre, " ", e.ap_paterno) AS nombre_empleado')
            ->selectRaw('COUNT(ticket.id_ticket) AS tickets_pendientes')
            ->selectRaw('e.email_empresa')
            ->selectRaw('u.id_empleado')
            ->join('empleado as e', 'ticket.id_destinatario', '=', 'e.id_empleado')
            ->join('estado_ticket as et', 'ticket.id_estado', '=', 'et.id_estado')
            ->join('op_user as u', 'u.id_empleado', '=', 'e.id_empleado')
            ->where('e.activo', 1)
            ->where('et.id_estado', '!=', 3)
            ->groupBy('e.nombre', 'e.ap_paterno', 'e.email_empresa', 'u.id_empleado')
            ->orderBy('tickets_pendientes', 'DESC')
            ->get();
    }

    public function get_top_ten()
    {
        return $this->selectRaw('u.id_user')
            ->selectRaw('e.nombre')
            ->selectRaw('e.email_empresa')
            ->selectRaw('ticket.id_destinatario')
            ->selectRaw('COUNT(ticket.id_ticket) AS total')
            ->join('empleado as e', 'ticket.id_destinatario', '=', 'e.id_empleado')
            ->join('op_user as u', 'ticket.id_destinatario', '=', 'u.id_empleado')
            ->where('ticket.id_estado', 3)
            ->where('e.activo', true)
            ->groupBy('u.id_user', 'e.nombre', 'e.email_empresa', 'ticket.id_destinatario')
            ->orderBy('total', 'DESC')
            ->limit(10)
            ->get();
    }
}
    

