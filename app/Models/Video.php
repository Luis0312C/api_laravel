<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    protected $table = 'videos';
    protected $primaryKey = 'id';

    public function get_titulo()
    {
        return $this->titulo;
    }

    public function get_url()
    {
        return $this->video_url;
    }

    public function get_categoria()
    {
        return $this->categoria_id;    
    }

    public function get_depto()
    {
        return $this->depto_id;        
    }

    public function get_creacion()
    {
        return $this->fecha_creacion;        
    }

    public static function getVideosYoutube(){
        return self::whereNull('depto_id')->get();
    }

    public static function getVideosDepartamento(){
        return self::whereNotNull('depto_id')->get();
    }

}
