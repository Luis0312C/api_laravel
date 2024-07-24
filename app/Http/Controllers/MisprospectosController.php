<?php

namespace App\Http\Controllers;

use App\Models\Prospecto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MisprospectosController extends Controller
{
    public function getProspectos()
    {
        $id_empleado = auth()->user()->id_empleado;

        $prospecto = new Prospecto();
        $prospectos = $prospecto->get_prospectos_por_empleado($id_empleado);

        $data = [
            'titulo' => 'Mi lista de prospectos',
            'prospectos' => $prospectos,
        ];

        return response()->json($data);
    }
}
