<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    
    public function index(){
        $videos = Video::getVideosYoutube();

        $data = [
            "videos_youtube" => $videos->map(function($videos){
                return[
                    "titulo" => $videos->get_titulo(),
                    "video_url" => $videos->get_url(),
                    "categoria_id" => $videos->get_categoria(),
                    "depto_id" => $videos->get_depto(),
                    "fecha_creacion" => $videos->get_creacion(),
                ];
            })
        ];
        return response()->json($data);
    }

    public function departamento(){
        $videos = Video::getVideosDepartamento();

        $data =[
            "videos_departamento" => $videos->map(function($videos){
                return[
                    "titulo" => $videos->get_titulo(),
                    "video_url" => $videos->get_url(),
                    "categoria_id" => $videos->get_categoria(),
                    "depto_id" => $videos->get_depto(), 
                    "fecha_creacion" => $videos->get_creacion(),
                ];
            })
        ];

        return response()->json($data);

    }
}
