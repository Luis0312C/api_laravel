<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Departamento;
use App\Models\Empleado;
use App\Models\Region;
use Illuminate\Http\Request;

class CuentaController extends Controller
{
    public function index()
    {
        $userData = auth()->user();
        $id_empleado = $userData->id_empleado;

        $empleadoModel = new Empleado();
        // $departamentoModel = new Departamento();
        // $regionModel = new Region();

        $info_personal = $empleadoModel->getEmpleados($id_empleado);
        // $deptos = $departamentoModel->getDepartamentos();
        // $regiones = $regionModel->getRegiones();

        $data = [
            'titulo' => 'Configuración de la cuenta',
            'titulo3' => 'Acerca de mi',
            'info_personal' => $info_personal,
        ];

        return response()->json($data);
    }
}