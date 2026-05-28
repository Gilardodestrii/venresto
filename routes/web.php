<?php

use App\Http\Controllers\Api\KitchenController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
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
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
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

    // Central login
    Route::get('/login', [LoginController::class, 'show'])
        ->name('central.login');

    Route::post('/login', [LoginController::class, 'store'])
        ->middleware('throttle:login');

    // Tenant login (legacy support)
    Route::get('/{tenant}/login', [LoginController::class, 'show'])
        ->name('login');

    Route::post('/{tenant}/login', [LoginController::class, 'store'])
        ->middleware('throttle:login');
});

Route::get('/{tenant}/admin/dashboard', function () {
    return view('admin.dashboard');
})->middleware(['auth'])->name('tenant.admin.dashboard');

Route::post('/logout', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::get('/', LandingController::class)->name('landing.home');
Route::view('/pricing', 'landing.pricing')->name('landing.pricing');
Route::view('/features', 'landing.features')->name('landing.features');
Route::get('/documentation', [LandingController::class, 'documentation'])->name('landing.documentation');

Route::view('/privacy', 'landing.privacy')->name('landing.privacy');
Route::view('/terms', 'landing.terms')->name('landing.terms');
Route::view('/contact', 'landing.contact')->name('landing.contact');

Route::post('/contact', function (Request $request) {
    $validated = $request->validate([
        'name' => ['required', 'string', 'max:100'],
        'email' => ['required', 'email', 'max:150'],
        'phone' => ['nullable', 'string', 'max:30'],
        'topic' => ['required', 'string', 'max:100'],
        'message' => ['required', 'string', 'max:5000'],
    ]);

    return back()->with('success', 'Pesan berhasil dikirim. Tim VenResto akan segera menghubungi Anda.');
})->name('landing.contact.submit');

Route::get('/signup', [SignupController::class,'show']);
Route::post('/signup', [SignupController::class,'store']);

Route::post('/webhooks/midtrans', MidtransWebhookController::class);
