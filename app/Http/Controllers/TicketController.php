<?php

namespace App\Http\Controllers;

use App\Models\MisTickets;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function getTicketsGenerados()
    {
        $userData = auth()->user();
        $id_empleado = $userData->id_empleado;

        $tickets = MisTickets::getTicketsGenerados($id_empleado);

        $data = [
            "tickets_generados" => [
                "titulo" => "Mis tickets generados",
                "tickets" => $tickets,
            ],
        ];
        
        return response()->json($data);
    }

    public function getTicketsAsociados(Request $request)
    {
        $userData = auth()->user();
        $id_empleado = $userData->id_empleado;
        $limite = $request->input('limite', 'todo');
        $tickets = MisTickets::getTicketsAsociados($id_empleado, $limite);
    
        $data = [
            "tickets_asociados" => [
                "titulo" => "Mis tickets asociados",
                "tickets" => $tickets,
            ],
        ];
        
        return response()->json($data);
    }
    
}
