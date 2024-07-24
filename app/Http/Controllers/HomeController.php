<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Empleado;
use App\Models\Prospecto;
use App\Models\Prospectos;
use App\Models\Proyecto;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $userData = auth()->user();

        $empleado = Empleado::find($userData->id_empleado);
        $prospecto = new Prospecto();
        $proyecto = new Proyecto();
        $ticket = new Ticket();
        $date = Carbon::now();
        $id_empleado = auth()->user()->id_empleado;
        


        $data = [
            "estadisticas" => [
                "titulo" => "trayectoria",
                "estadisticas" => (object)$empleado->get_totales($userData->id_empleado)[0],

            ],
            "tickets_pendientes" => [
                "titulo" => "Tickets pendientes",
                "pendientes" => $ticket->get_total_tickets_pendientes(),
            ],
            "top_ten_tickets" => [
                "titulo" => "Top 10 tickets atendidos",
                "top" => $ticket->get_top_ten($date->format('Y-m-d H:i:s')),
            ],

            "prospectos" => [
                'titulo' => 'Mis prospectos recientes',
                'prospectos' => $prospecto->get_prospectos_por_empleado($id_empleado, 'LIMIT 5'),
            ],

            "clientes_recientes" => [
                "titulo" => "Mis clientes recientes",
                "proyectos" => $proyecto->get_top_nuevos($userData->id_empleado),
            ], 
            "tickets_asociados" => [
                "titulo" => "Tickets que me asignaron",
                "tickets_asociados" => $empleado->get_ultimos_tickets_asociados($userData->id_empleado),
            ],
            "tickets_generados" => [
                "titulo" => "Tickets que envié",
                "tickets_generados" => $empleado->get_ultimos_tickets_generados($userData->id_empleado),
            ],


        ];

        return response()->json($data);
    }
}
