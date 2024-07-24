<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AsociadosController extends Controller
{
    public function asociados(Request $request){

        $userData = auth::user();
        $id_empleado = $userData->id_empleado; 

        $limite = $request->input('limite', '');
        $empleado = new Empleado();

        $tickets_asociados = $empleado->get_tickets_asociados($id_empleado, $limite);

        return response()->json([
            'status' => true,
            'message' => 'Tikets asociados',
            'data' => [
                'titulo' => 'Tickets asociados a mi perfil',
                'tickets_asociados' => $tickets_asociados,
            ],
        ]);
    }
}
