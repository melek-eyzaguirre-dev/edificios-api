<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdministradoraController;
use App\Http\Controllers\CondominioController;
use App\Http\Controllers\UnidadController;
use App\Http\Controllers\EspacioComunController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\NovedadController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\GastoComunController;
use App\Http\Controllers\TurnoController;
use App\Http\Controllers\VisitaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AsistenciaController;
use App\Http\Controllers\EstacionamientoController;
use App\Http\Controllers\PersonalController;
use App\Http\Controllers\SolicitudAusenciaController;
use App\Http\Controllers\ResidenteController;
use App\Http\Controllers\FeedbackResidenteController;
use App\Http\Controllers\EstacionamientoOcupacionController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
 
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register-residente', [AuthController::class, 'registerResident']);
 
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
 
    // Registro solo lo puede hacer un admin logueado (no es auto-registro público)
    Route::post('/register', [AuthController::class, 'register'])
        ->middleware('rol:super_admin,admin_administradora,admin_condominio');
});
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('administradoras', AdministradoraController::class)->only(['index', 'store', 'show']);
    Route::apiResource('condominios', CondominioController::class)->only(['index', 'store', 'show']);
    Route::apiResource('unidades', UnidadController::class)->only(['index', 'store', 'show']);
    Route::apiResource('personal', PersonalController::class)->only(['index', 'store', 'update', 'destroy'])
        ->middleware('rol:super_admin,admin_administradora,admin_condominio');
    Route::apiResource('espacios-comunes', EspacioComunController::class)->only(['index', 'store']);
    Route::apiResource('reservas', ReservaController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::apiResource('visitas', VisitaController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::post('visitas/{visita}/reportar-no-autorizada', [VisitaController::class, 'reportarNoAutorizada']);
    Route::apiResource('estacionamientos', EstacionamientoController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::apiResource('turnos', TurnoController::class)->only(['index', 'store']);
    Route::apiResource('asistencias', AsistenciaController::class)->only(['index', 'store']);
    Route::apiResource('solicitudes-ausencia', SolicitudAusenciaController::class)->only(['index', 'store']);
    Route::patch('solicitudes-ausencia/{solicitudAusencia}', [SolicitudAusenciaController::class, 'update']);
    Route::post('asistencias/{asistencia}/entrada', [AsistenciaController::class, 'marcarEntrada']);
    Route::post('asistencias/{asistencia}/salida', [AsistenciaController::class, 'marcarSalida']);
    Route::get('dashboard/summary', [DashboardController::class, 'summary']);
    Route::get('residente/summary', [ResidenteController::class, 'summary']);
    Route::apiResource('feedback-residentes', FeedbackResidenteController::class)->only(['index', 'store']);
    Route::apiResource('ocupaciones-estacionamiento', EstacionamientoOcupacionController::class)->only(['index', 'store']);

    Route::apiResource('novedades', NovedadController::class)->only(['index', 'store', 'update']);
    Route::apiResource('proveedores', ProveedorController::class)->only(['index', 'store', 'update']);
    Route::get('inventario', [InventarioController::class, 'index']);
    Route::post('inventario', [InventarioController::class, 'store']);
    Route::post('inventario/{articuloInventario}/movimientos', [InventarioController::class, 'movement']);
    Route::get('gastos-comunes', [GastoComunController::class, 'index']);
    Route::get('gastos-comunes/{periodoGastoComun}/detalle', [GastoComunController::class, 'detail']);
    Route::post('gastos-comunes', [GastoComunController::class, 'store']);
    Route::post('gastos-comunes/{periodoGastoComun}/cargos', [GastoComunController::class, 'charges']);
    Route::post('gastos-comunes/{periodoGastoComun}/distribuir', [GastoComunController::class, 'distribute']);
    Route::post('gastos-comunes/cargos/{cargoGastoComun}/pagos', [GastoComunController::class, 'pay']);
});