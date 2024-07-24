<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Region extends Model
{
    
    protected $table = 'region';
    protected $primaryKey = 'id_region';

    public function getRegiones($id = 0)
    {
        if ($id == 0) {
            return DB::select("
                SELECT 
                    region.id_region AS id, 
                    region.nombre,
                    COUNT(cliente.id_region) AS total
                FROM region 
                LEFT JOIN cliente ON cliente.id_region = region.id_region AND cliente.activo
                GROUP BY region.id_region, region.nombre
            ");
        } else {
            return DB::select("
                SELECT id_region AS id, nombre 
                FROM region 
                WHERE id_region = ?
            ", [$id]);
        }
    }
}