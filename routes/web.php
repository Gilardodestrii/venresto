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
use App\Http\Controllers\Central\RoleManagementController;
use App\Http\Controllers\Central\InventoryReportController;
use App\Http\Controllers\Central\SalesReportController;
use App\Http\Controllers\Central\ProfitReportController;
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
        })->middleware('permission:dashboard.view')->name('dashboard');

        Route::get('/roles', [RoleManagementController::class, 'index'])->middleware('permission:users.manage')->name('roles.index');
        Route::put('/roles/users/{user}', [RoleManagementController::class, 'update'])->middleware('permission:users.manage')->name('roles.update');

        Route::get('/reports/inventory', [InventoryReportController::class, 'index'])->middleware('permission:reports.view')->name('reports.inventory');
        Route::get('/reports/inventory/export', [InventoryReportController::class, 'export'])->middleware('permission:reports.view')->name('reports.inventory.export');
        Route::get('/reports/sales', [SalesReportController::class, 'index'])->middleware('permission:reports.view')->name('reports.sales');
        Route::get('/reports/sales/export', [SalesReportController::class, 'export'])->middleware('permission:reports.view')->name('reports.sales.export');
        Route::get('/reports/profit', [ProfitReportController::class, 'index'])->middleware('permission:reports.view')->name('reports.profit');

        Route::resource('outlets', OutletController::class)->middleware('permission:outlet.manage');

        Route::get('/outlets/{outlet}/qr', [QrAdminController::class, 'index'])->middleware('permission:outlet.manage')->name('qr.index');
        Route::get('/outlets/{outlet}/qr/generate/{table}', [QrAdminController::class, 'generate'])->middleware('permission:outlet.manage')->name('qr.generate');
        Route::get('/outlets/{outlet}/qr/download/{table}', [QrAdminController::class, 'download'])->middleware('permission:outlet.manage')->name('qr.download');
        Route::post('/outlets/{outlet}/qr/store', [QrAdminController::class, 'store'])->middleware('permission:outlet.manage')->name('qr.store');
        Route::get('/outlets/{outlet}/qr/destroy/{table}', [QrAdminController::class, 'destroy'])->middleware('permission:outlet.manage')->name('qr.destroy');

        Route::resource('menu-categories', MenuCategoryController::class)->middleware('permission:menu.manage');
        Route::resource('menu-items', MenuItemController::class)->middleware('permission:menu.manage');

        Route::get('/pos', [PosController::class, 'index'])->middleware('permission:pos.access')->name('pos.index');
        Route::post('/pos/store', [PosController::class, 'store'])->middleware('permission:pos.access')->name('pos.store');

        Route::get('/orders', [OrderController::class, 'index'])->middleware('permission:orders.view')->name('orders.index');
        Route::get('/orders/{order}', [OrderController::class, 'show'])->middleware('permission:orders.view')->name('orders.show');
        Route::post('/orders/{order}/payment', [OrderController::class, 'updatePayment'])->middleware('permission:orders.pay')->name('orders.updatePayment');
        Route::post('/orders/{order}/void', [OrderController::class, 'void'])->middleware('permission:orders.void')->name('orders.void');

        Route::get('/cashier-sessions', [CashierSessionController::class, 'index'])->middleware('permission:pos.access')->name('cashier-sessions.index');
        Route::post('/cashier-sessions/open', [CashierSessionController::class, 'open'])->middleware('permission:pos.access')->name('cashier-sessions.open');
        Route::post('/cashier-sessions/{session}/close', [CashierSessionController::class, 'close'])->middleware('permission:pos.access')->name('cashier-sessions.close');

        Route::get('/kitchen', [KitchenDisplayController::class, 'index'])->middleware('permission:kitchen.access')->name('kitchen.index');
        Route::get('/kitchen/live', [KitchenDisplayController::class, 'live'])->middleware('permission:kitchen.access')->name('kitchen.live');
        Route::post('/kitchen/item/{id}/status', [KitchenDisplayController::class, 'updateStatus'])->middleware('permission:kitchen.access')->name('kitchen.item.status');

        Route::resource('materials', MaterialController::class)->except(['show'])->middleware('permission:inventory.manage');
        Route::post('/materials/{material}/stock-in', [StockMovementController::class, 'stockIn'])->middleware('permission:inventory.manage')->name('materials.stock-in');
        Route::post('/materials/{material}/stock-out', [StockMovementController::class, 'stockOut'])->middleware('permission:inventory.manage')->name('materials.stock-out');
        Route::post('/materials/{material}/adjustment', [StockMovementController::class, 'adjustment'])->middleware('permission:inventory.manage')->name('materials.adjustment');

        Route::get('/recipes', [RecipeController::class, 'index'])->middleware('permission:recipe.manage')->name('recipes.index');
        Route::get('/recipes/create', [RecipeController::class, 'create'])->middleware('permission:recipe.manage')->name('recipes.create');
        Route::post('/recipes', [RecipeController::class, 'store'])->middleware('permission:recipe.manage')->name('recipes.store');
        Route::delete('/recipes/{recipe}', [RecipeController::class, 'destroy'])->middleware('permission:recipe.manage')->name('recipes.destroy');

        Route::get('/menu-costing', [MenuCostingController::class, 'index'])->middleware('permission:costing.view')->name('menu-costing.index');

        Route::get('/stock-movements', [StockMovementController::class, 'index'])->middleware('permission:stock.movement.view')->name('stock-movements.index');

        Route::get('/stock-transfers', [StockTransferController::class, 'index'])->middleware('permission:stock.transfer')->name('stock-transfers.index');
        Route::get('/stock-transfers/create', [StockTransferController::class, 'create'])->middleware('permission:stock.transfer')->name('stock-transfers.create');
        Route::post('/stock-transfers', [StockTransferController::class, 'store'])->middleware('permission:stock.transfer')->name('stock-transfers.store');
        Route::get('/stock-transfers/{stockTransfer}', [StockTransferController::class, 'show'])->middleware('permission:stock.transfer')->name('stock-transfers.show');
        Route::post('/stock-transfers/{stockTransfer}/complete', [StockTransferController::class, 'complete'])->middleware('permission:stock.transfer')->name('stock-transfers.complete');
        Route::post('/stock-transfers/{stockTransfer}/cancel', [StockTransferController::class, 'cancel'])->middleware('permission:stock.transfer')->name('stock-transfers.cancel');

        Route::get('/waste-records', [WasteRecordController::class, 'index'])->middleware('permission:waste.manage')->name('waste-records.index');
        Route::get('/waste-records/create', [WasteRecordController::class, 'create'])->middleware('permission:waste.manage')->name('waste-records.create');
        Route::post('/waste-records', [WasteRecordController::class, 'store'])->middleware('permission:waste.manage')->name('waste-records.store');
        Route::get('/waste-records/{wasteRecord}', [WasteRecordController::class, 'show'])->middleware('permission:waste.manage')->name('waste-records.show');
    });
