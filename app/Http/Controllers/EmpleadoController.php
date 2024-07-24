<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Empleado;
use App\Models\Inactivo;

class EmpleadoController extends Controller
{
    public function index()
    {
        $userData = auth()->user();
        $id_empleado = $userData->id_empleado;

        $data = [
            "clientes_recientes" => [
                "titulo" => "Mis clientes recientes",
                "clientes" => Cliente::clientesPorEmpleado($id_empleado)->get(),
            ],
        ];
        
        return response()->json($data);
    }

    public function inactivos()
    {
        $userData = auth()->user();
        $id_empleado = $userData->id_empleado;

        $data = [
            "clientes_recientes" => [
                "titulo" => "Mis clientes inactivos",
                "clientes" => Inactivo::clientesPorEmpleado($id_empleado)->get(),
            ],
        ];
        
        return response()->json($data);
    }

    public function getGravatar($size)
    {
        $userData = auth()->user();
        $idEmpleado = $userData->id_empleado;

        $empleado = new Empleado();
        $emailData = $empleado->get_img($idEmpleado);

        if (!empty($emailData) && isset($emailData[0]->email_empresa)) {
            $email = $emailData[0]->email_empresa;
            $gravatarUrl = $this->gravatar($email, $size);
            return response()->json(['gravatar_url' => $gravatarUrl], 200);
        } else {
            return response()->json(['message' => 'Employee not found'], 404);
        }
    }

    private function gravatar($email, $size)
    {
        return 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($email))) . '?s=' . $size;
    }

}
