<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\AsociadosController;
use App\Http\Controllers\ClienteactivoController;
use App\Http\Controllers\ClienteinactivoController;
use App\Http\Controllers\CuentaController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MisprospectosController;
use App\Http\Controllers\ProspeccionController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\DocumentoController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\VideoController;

// Rutas abiertas
Route::post('/login', [ApiController::class, 'login']); // Ruta para iniciar sesión
Route::post('/reset-password', [ApiController::class, 'resetPassword']);

// Rutas protegidas que requieren autenticación API
Route::group(["middleware" => ["auth:api"]], function(){
    Route::get("profile", [ApiController::class, "profile"]); 
    Route::get("logout", [ApiController::class, "logout"]); 
    Route::get('home', [HomeController::class, 'index']);
    Route::get('/url', [DocumentoController::class, 'index']); 
    Route::get('/subcategoria', [DocumentoController::class, 'subcategoria']);
    Route::get('/youtube', [VideoController::class, 'index']); 
    Route::get('/departamento', [VideoController::class, 'departamento']); 
    Route::get('/prospectos', [ProspeccionController::class, 'index']); 
    Route::get('/mis-prospectos', [MisprospectosController::class, 'getProspectos']);
    Route::get('/clientes_recientes', [EmpleadoController::class, 'index'])->middleware('auth');
    Route::get('/clientes_inactivos', [EmpleadoController::class, 'inactivos'])->middleware('auth');
    Route::get('/empleado/gravatar/{size}', [EmpleadoController::class, 'getGravatar']);
    Route::get('/tickets-generados', [TicketController::class, 'getTicketsGenerados']);
    Route::get('/tickets-asociados', [TicketController::class, 'getTicketsAsociados']);
    Route::get('/cuenta', [CuentaController::class, 'index']);




    // Route::get('/generados', [TicketController::class, 'generados']); 
    // Route::get('/asociados', [AsociadosController::class, 'asociados']); 
    
    // Route::get('/informacion-usuario', [EmpleadoController::class, 'informacion']); 

    // Route::get('/activos/{id?}/{status?}', [ClienteactivoController::class, 'index']);
    // Route::get('/inactivos/{id?}/{status?}', [ClienteinactivoController::class, 'index']);
});
