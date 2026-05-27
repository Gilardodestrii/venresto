<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Central\LandingController;
use App\Http\Controllers\Central\LoginController;
use App\Http\Controllers\Central\SignupController;
use App\Http\Controllers\Central\MidtransWebhookController;
use App\Http\Controllers\Central\QrAdminController;
use App\Http\Controllers\Central\OutletController;
use App\Http\Controllers\Central\MenuItemController;
use App\Http\Controllers\Central\MenuCategoryController;
use App\Http\Controllers\Central\PosController;
use App\Http\Controllers\Central\KitchenDisplayController;
use App\Http\Controllers\Central\OrderController;
use App\Http\Controllers\Central\CashierSessionController;
use App\Http\Controllers\Central\MaterialController;
use App\Http\Controllers\Central\RecipeController;
use App\Http\Controllers\Central\StockMovementController;
use App\Http\Controllers\Central\StockTransferController;
use App\Http\Controllers\Central\WasteRecordController;
use App\Http\Controllers\Central\MenuCostingController;
use App\Http\Controllers\QrMenuController;

Route::get('/env-check', function () {
    return [
        'host' => env('DB_HOST'),
        'port' => env('DB_PORT'),
        'database' => env('DB_DATABASE'),
        'username' => env('DB_USERNAME'),
    ];
});

Route::get('/db-check', function () {
    try {
        DB::connection()->getPdo();

        return [
            'success' => true,
            'db' => DB::connection()->getDatabaseName(),
        ];
    } catch (\Throwable $e) {
        return [
            'error' => $e->getMessage(),
        ];
    }
});

Route::get('/', LandingController::class)->name('landing.home');
Route::view('/pricing', 'landing.pricing')->name('landing.pricing');
Route::view('/features', 'landing.features')->name('landing.features');
Route::get('/documentation', [LandingController::class, 'documentation'])->name('landing.documentation');

Route::get('/signup', [SignupController::class, 'show'])->name('signup.show');
Route::post('/signup', [SignupController::class, 'store'])->name('signup.store');

Route::middleware('guest')->group(function () {
    Route::get('/{tenant}/login', [LoginController::class, 'show'])->name('login');
    Route::post('/{tenant}/login', [LoginController::class, 'store'])->middleware('throttle:login');
});

Route::post('/logout', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::post('/webhooks/midtrans', MidtransWebhookController::class);

Route::prefix('{tenant}/qr')->group(function () {
    Route::get('/{table}', [QrMenuController::class, 'index'])->name('qr.menu');
    Route::post('/order', [QrMenuController::class, 'store'])->name('qr.order.store');
});

Route::middleware(['auth'])
    ->prefix('{tenant}/admin')
    ->name('tenant.admin.')
    ->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        Route::resource('outlets', OutletController::class);

        Route::get('/outlets/{outlet}/qr', [QrAdminController::class, 'index'])->name('qr.index');
        Route::get('/outlets/{outlet}/qr/generate/{table}', [QrAdminController::class, 'generate'])->name('qr.generate');
        Route::get('/outlets/{outlet}/qr/download/{table}', [QrAdminController::class, 'download'])->name('qr.download');
        Route::post('/outlets/{outlet}/qr/store', [QrAdminController::class, 'store'])->name('qr.store');
        Route::get('/outlets/{outlet}/qr/destroy/{table}', [QrAdminController::class, 'destroy'])->name('qr.destroy');

        Route::resource('menu-categories', MenuCategoryController::class);
        Route::resource('menu-items', MenuItemController::class);

        Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
        Route::post('/pos/store', [PosController::class, 'store'])->name('pos.store');

        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
        Route::post('/orders/{order}/payment', [OrderController::class, 'updatePayment'])->name('orders.updatePayment');
        Route::post('/orders/{order}/void', [OrderController::class, 'void'])->name('orders.void');

        Route::get('/cashier-sessions', [CashierSessionController::class, 'index'])->name('cashier-sessions.index');
        Route::post('/cashier-sessions/open', [CashierSessionController::class, 'open'])->name('cashier-sessions.open');
        Route::post('/cashier-sessions/{session}/close', [CashierSessionController::class, 'close'])->name('cashier-sessions.close');

        Route::get('/kitchen', [KitchenDisplayController::class, 'index'])->name('kitchen.index');
        Route::get('/kitchen/live', [KitchenDisplayController::class, 'live'])->name('kitchen.live');
        Route::post('/kitchen/item/{id}/status', [KitchenDisplayController::class, 'updateStatus'])->name('kitchen.item.status');

        Route::resource('materials', MaterialController::class)->except(['show']);
        Route::post('/materials/{material}/stock-in', [StockMovementController::class, 'stockIn'])->name('materials.stock-in');
        Route::post('/materials/{material}/stock-out', [StockMovementController::class, 'stockOut'])->name('materials.stock-out');
        Route::post('/materials/{material}/adjustment', [StockMovementController::class, 'adjustment'])->name('materials.adjustment');

        Route::get('/recipes', [RecipeController::class, 'index'])->name('recipes.index');
        Route::get('/recipes/create', [RecipeController::class, 'create'])->name('recipes.create');
        Route::post('/recipes', [RecipeController::class, 'store'])->name('recipes.store');
        Route::delete('/recipes/{recipe}', [RecipeController::class, 'destroy'])->name('recipes.destroy');

        Route::get('/menu-costing', [MenuCostingController::class, 'index'])->name('menu-costing.index');

        Route::get('/stock-movements', [StockMovementController::class, 'index'])->name('stock-movements.index');

        Route::get('/stock-transfers', [StockTransferController::class, 'index'])->name('stock-transfers.index');
        Route::get('/stock-transfers/create', [StockTransferController::class, 'create'])->name('stock-transfers.create');
        Route::post('/stock-transfers', [StockTransferController::class, 'store'])->name('stock-transfers.store');
        Route::get('/stock-transfers/{stockTransfer}', [StockTransferController::class, 'show'])->name('stock-transfers.show');
        Route::post('/stock-transfers/{stockTransfer}/complete', [StockTransferController::class, 'complete'])->name('stock-transfers.complete');
        Route::post('/stock-transfers/{stockTransfer}/cancel', [StockTransferController::class, 'cancel'])->name('stock-transfers.cancel');

        Route::get('/waste-records', [WasteRecordController::class, 'index'])->name('waste-records.index');
        Route::get('/waste-records/create', [WasteRecordController::class, 'create'])->name('waste-records.create');
        Route::post('/waste-records', [WasteRecordController::class, 'store'])->name('waste-records.store');
        Route::get('/waste-records/{wasteRecord}', [WasteRecordController::class, 'show'])->name('waste-records.show');
    });
