<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ParkingSpaceController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\InformationController;
use App\Http\Controllers\ExchangeRateController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\PriceController;
use App\Http\Controllers\RecordController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ShiftController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rutas Públicas (Acceso General)
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/mapa', [ParkingSpaceController::class, 'publicIndex'])->name('mapa');

Route::get('/sobre-nosotros', function () {
    return view('sobre-nosotros');
})->name('sobre-nosotros');

/*
|--------------------------------------------------------------------------
| Rutas Privadas (Solo Usuarios Autenticados y Verificados)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    // Perfil de Usuario (Todos pueden editar su propio perfil)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ====================================================================
    // ZONA OPERATIVA: Acceso para CAJEROS (y Administrador)
    // ====================================================================
    Route::middleware([\App\Http\Middleware\CheckRole::class . ':Cajero'])->group(function () {

        // Mapa y Registro
        Route::get('/dashboard', function () {
            $spaces = \App\Models\ParkingSpace::all();
            return view('dashboard', compact('spaces'));
        })->name('dashboard');

        Route::post('/tickets/entrada', [TicketController::class, 'storeEntry'])->name('tickets.storeEntry');
        Route::post('/escanear-placa', [TicketController::class, 'scanPlate'])->name('tickets.scan');
        Route::get('/tickets/calcular/{space_id}', [TicketController::class, 'calculateFee'])->name('tickets.calculate');
        Route::post('/tickets/facturar', [TicketController::class, 'processCheckout'])->name('tickets.processCheckout');
        Route::post('/tickets/{ticket}/cancelar', [TicketController::class, 'cancelTicket'])->name('tickets.cancel');
        Route::post('/tickets/absolver', [TicketController::class, 'waivePayment'])->name('tickets.waive');

        // Directorio de Clientes y Búsquedas
        Route::get('/clientes', [ClientController::class, 'index'])->name('clients.index');
        Route::put('/clientes/{client}', [ClientController::class, 'update'])->name('clients.update');
        Route::get('/clientes/buscar/{plate}', [ClientController::class, 'search'])->name('clients.search');
        Route::get('/clientes/buscar-doc/{documento}', [TicketController::class, 'searchClientByDocument'])->name('clients.search.doc');

        // Control de Caja / Turnos del Cajero
        Route::post('/turnos/abrir', [ShiftController::class, 'openShift'])->name('shifts.open');
        Route::post('/turnos/cerrar', [ShiftController::class, 'closeShift'])->name('shifts.close');
    });

    // ====================================================================
    // ZONA ADMINISTRATIVA: Acceso para OPERADORES (y Administrador)
    // ====================================================================
    Route::middleware([\App\Http\Middleware\CheckRole::class . ':Operador'])->group(function () {

        // Gestión de Usuarios y Roles
        Route::get('/usuarios', [UserController::class, 'index'])->name('users.index');
        Route::post('/usuarios', [UserController::class, 'store'])->name('users.store');
        Route::put('/usuarios/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/usuarios/{user}', [UserController::class, 'destroy'])->name('users.destroy');

        Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
        Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
        Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');

        // Configuración Comercial
        Route::get('/precios', [PriceController::class, 'index'])->name('prices.index');
        Route::post('/precios', [PriceController::class, 'store'])->name('prices.store');
        Route::put('/precios/{price}', [PriceController::class, 'update'])->name('prices.update');
        Route::delete('/precios/{price}', [PriceController::class, 'destroy'])->name('prices.destroy');

        Route::get('/puestos', [ParkingSpaceController::class, 'index'])->name('parking.spaces.index');
        Route::post('/puestos', [ParkingSpaceController::class, 'store'])->name('parking.spaces.store');
        Route::delete('/puestos/{space}', [ParkingSpaceController::class, 'destroy'])->name('parking.spaces.destroy');
        Route::post('/puestos/bulk-delete', [ParkingSpaceController::class, 'bulkDestroy'])->name('parking.spaces.bulkDestroy');

        // Configuraciones y Tasas
        Route::get('/configuracion/informacion', [InformationController::class, 'index'])->name('information.index');
        Route::post('/configuracion/informacion', [InformationController::class, 'store'])->name('information.store');
        Route::get('/configuracion/tasa', [ExchangeRateController::class, 'index'])->name('tasa.index');
        Route::post('/configuracion/tasa', [ExchangeRateController::class, 'update'])->name('tasa.update');

        // Auditoría e Historial general
        Route::get('/registros', [RecordController::class, 'index'])->name('records.index');
        Route::get('/auditoria/turnos', [ShiftController::class, 'index'])->name('shifts.index');
    });

    // ====================================================================
    // ZONA MIXTA: Acceso para AMBOS (Cajeros y Operadores)
    // ====================================================================
    Route::middleware([\App\Http\Middleware\CheckRole::class . ':Cajero,Operador'])->group(function () {
        Route::get('/tickets/{ticket}/print', [TicketController::class, 'printTicket'])->name('tickets.print');
        Route::get('/invoices/{invoice}/print', [TicketController::class, 'printInvoice'])->name('invoices.print');
        Route::get('/turnos/{shift}/imprimir-z', [ShiftController::class, 'printZReport'])->name('shifts.printZ');
        Route::get('/turnos/{shift}/imprimir-ventas', [ShiftController::class, 'printSalesSummary'])->name('shifts.printSales');
    });
});

require __DIR__.'/auth.php';
