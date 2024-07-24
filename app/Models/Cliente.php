<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'cliente';
    protected $primaryKey = 'id_cliente';

    protected $fillable = [
        'id_cliente', 'nombre', 'ap_paterno', 'ap_materno', 'dominio', 'creacion_dominio',
        'nombre_empresa', 'email_empresa', 'telefono_casa', 'telefono_movil', 'fecha_ingreso',
        'usuario_skype', 'id_region', 'activo'
    ];

    public function region()
    {
        return $this->belongsTo(Region::class, 'id_region', 'id_region');
    }

    public function integrantesProyecto()
    {
        return $this->hasMany(Integrante_proyecto::class, 'id_cliente', 'id_cliente');
    }

    public function scopeClientesPorEmpleado($query, $id_empleado, $activo = 1)
    {
        return $query->select(
            'cliente.id_cliente',
            DB::raw("CONCAT(cliente.nombre, ' ', cliente.ap_paterno, ' ', cliente.ap_materno) AS nombre"),
            'cliente.dominio',
            'cliente.creacion_dominio',
            'cliente.nombre_empresa',
            'cliente.email_empresa',
            'cliente.telefono_casa',
            'cliente.telefono_movil',
            'cliente.fecha_ingreso',
            'cliente.usuario_skype',
            'cliente.id_region',
            'cliente.activo',
            'region.nombre AS region'
        )
        ->join('region', 'cliente.id_region', '=', 'region.id_region')
        ->whereIn('cliente.id_cliente', function ($subquery) use ($id_empleado) {
            $subquery->select('id_cliente')
                     ->from('integrante_proyecto')
                     ->where('id_empleado', $id_empleado);
        })
        ->where('cliente.activo', $activo)
        ->orderBy('cliente.fecha_ingreso', 'desc');
    }
}
