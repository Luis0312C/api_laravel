<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Prospecto;
use Illuminate\Http\Request;

class ProspeccionController extends Controller
{
    public function index(){
        $prospecto = new Prospecto();

        
        $data = [
            "prospecto" =>[
                'titulo' => "Lista general de prospectos",
                "prospectos" => $prospecto->get_prospectos()
            ]
           
        ];

        return response()->json($data);
    }
}