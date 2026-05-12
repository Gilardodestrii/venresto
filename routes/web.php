<?php

use App\Http\Controllers\Api\KitchenController;
use Illuminate\Support\Facades\Route;
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




use App\Http\Controllers\QrMenuController;


Route::middleware('guest')->group(function () {
    Route::get('/{tenant}/login',  [LoginController::class, 'show'])->name('login');
    Route::post('/{tenant}/login', [LoginController::class, 'store'])->middleware('throttle:login');
});

Route::get('/{tenant}/admin/dashboard', function () {
    return view('admin.dashboard');
})->middleware(['auth']);

Route::post('/logout', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::get('/', LandingController::class);
Route::view('/pricing','landing.pricing');
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

        // CATEGORY
    Route::resource('{tenant}/admin/menu-categories', MenuCategoryController::class);

    // MENU ITEM
    Route::resource('{tenant}/admin/menu-items', MenuItemController::class);

    Route::prefix('{tenant}/admin')
        ->name('tenant.admin.')
        ->group(function () {

            Route::get('/pos', [PosController::class, 'index'])
                ->name('pos.index');

            Route::post('/pos/store', [PosController::class, 'store'])
                ->name('pos.store');

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

        Route::prefix('{tenant}/admin')
            ->name('tenant.admin.')
            ->group(function () {
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');

        Route::post('/orders/{order}/payment', [OrderController::class, 'updatePayment'])
            ->name('orders.updatePayment');
        }) ;


});