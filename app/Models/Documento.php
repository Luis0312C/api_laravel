<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    use HasFactory;

    protected $table = 'documentos';
    protected $primaryKey = 'id';
    

    public function get_titulo()
    {
        return $this->titulo;
    }
    
    public function get_url()
    {
        return $this->archivo_url;
    }

    
    public function get_categoria()
    {
        return $this->categoria_id;
    }

    public function get_subcategoria()
    {
        return $this->subcategoria_id;
    }

    public static function getDocumentosSinSubcategoria()
    {
        return self::whereNull('subcategoria_id')->get();
    }

    public static function getDocumentosConSubcategoria()
    {
        return self::whereNotNull('subcategoria_id')->get();
    }
}
