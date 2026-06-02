<?php

use App\Http\Controllers\Api\KitchenController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Central\LandingController;
use App\Http\Controllers\Central\LoginController;
use App\Http\Controllers\Central\SignupController;
use App\Http\Controllers\Central\GoogleTrialSignupController;
use App\Http\Controllers\Central\MidtransWebhookController;
use App\Http\Controllers\Central\QrAdminController;
use App\Http\Controllers\Central\OutletController;
use App\Http\Controllers\Central\MenuItemController;
use App\Http\Controllers\Central\MenuCategoryController;
use App\Http\Controllers\Central\PosController;
use App\Http\Controllers\Central\KitchenDisplayController;
use App\Http\Controllers\Central\OrderController;
use App\Http\Controllers\Central\CashierSessionController;
use Illuminate\Support\Facades\DB;
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
            'db' => DB::connection()->getDatabaseName()
        ];
    } catch (\Throwable $e) {
        return [
            'error' => $e->getMessage()
        ];
    }
});

Route::middleware('guest')->group(function () {
    Route::get('/{tenant}/login',  [LoginController::class, 'show'])->name('login');
    Route::post('/{tenant}/login', [LoginController::class, 'store'])->middleware('throttle:login');

    Route::get('/signup/google/redirect', [GoogleTrialSignupController::class, 'redirect'])
        ->name('signup.google.redirect');

    Route::get('/signup/google/callback', [GoogleTrialSignupController::class, 'callback'])
        ->name('signup.google.callback');

    Route::get('/signup/google/complete', [GoogleTrialSignupController::class, 'complete'])
        ->name('signup.google.complete');

    Route::post('/signup/google/complete', [GoogleTrialSignupController::class, 'store'])
        ->name('signup.google.store');
});

Route::get('/{tenant}/admin/dashboard', function () {
    return view('admin.dashboard');
})->middleware(['auth']);

Route::post('/logout', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::get('/', LandingController::class)->name('landing.home');
Route::view('/pricing', 'landing.pricing')->name('landing.pricing');
Route::view('/features', 'landing.features')->name('marketing.features');

Route::get('/signup', [SignupController::class,'show']);
Route::post('/signup', [SignupController::class,'store']);

Route::post('/webhooks/midtrans', MidtransWebhookController::class);

Route::group(['prefix' => '{tenant}/qr'], function () {

    Route::get('/{table}', [QrMenuController::class, 'index'])
        ->name('qr.menu');

    Route::post('/order', [QrMenuController::class, 'store'])
        ->name('qr.order.store');

});

Route::middleware(['auth'])->group(function () {

    Route::get('{tenant}/admin/outlets/{outlet}/qr', [QrAdminController::class, 'index'])
        ->name('admin.qr.index');

    Route::get('{tenant}/admin/outlets/{outlet}/qr/generate/{table}', [QrAdminController::class, 'generate'])
        ->name('admin.qr.generate');

    Route::get('{tenant}/admin/outlets/{outlet}/qr/download/{table}', [QrAdminController::class, 'download'])
        ->name('admin.qr.download');

    Route::post('{tenant}/admin/outlets/{outlet}/qr/store', [QrAdminController::class, 'store'])
        ->name('admin.qr.store');

    Route::get('{tenant}/admin/outlets/{outlet}/qr/destroy/{table}', [QrAdminController::class, 'destroy'])
        ->name('admin.qr.destroy');

    Route::resource(
        '{tenant}/admin/outlets',
        OutletController::class
    )->names('tenant.admin.outlets');

    Route::resource('{tenant}/admin/menu-categories', MenuCategoryController::class);
    Route::resource('{tenant}/admin/menu-items', MenuItemController::class);

    Route::prefix('{tenant}/admin')
        ->name('tenant.admin.')
        ->group(function () {

            Route::get('/pos', [PosController::class, 'index'])
                ->name('pos.index');

            Route::post('/pos/store', [PosController::class, 'store'])
                ->name('pos.store');

            Route::get('/orders', [OrderController::class, 'index'])
                ->name('orders.index');

            Route::get('/orders/{order}', [OrderController::class, 'show'])
                ->name('orders.show');

            Route::post('/orders/{order}/payment', [OrderController::class, 'updatePayment'])
                ->name('orders.updatePayment');

            Route::get('/cashier-sessions', [CashierSessionController::class, 'index'])
                ->name('cashier-sessions.index');

            Route::post('/cashier-sessions/open', [CashierSessionController::class, 'open'])
                ->name('cashier-sessions.open');

            Route::post('/cashier-sessions/{session}/close', [CashierSessionController::class, 'close'])
                ->name('cashier-sessions.close');
        });

    Route::prefix('{tenant}/admin/kitchen')
        ->name('kitchen.')
        ->group(function () {

            Route::get('/', [KitchenDisplayController::class, 'index'])
                ->name('index');

            Route::get('/live', [KitchenDisplayController::class, 'live'])
                ->name('live');

            Route::post(
                '/item/{id}/status',
                [KitchenDisplayController::class, 'updateStatus']
            )->name('item.status');

        });
});
