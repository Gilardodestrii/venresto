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

            Route::get('/reports/inventory', function () {
                return view('admin.reports.inventory');
            })->name('reports.inventory');

            Route::get('/settings', function () {
                $tenant = \App\Services\TenantContext::get();
                $settings = DB::table('tenant_settings')
                    ->where('tenant_id', $tenant?->id)
                    ->first();

                return view('admin.settings.index', compact('settings'));
            })->name('settings.index');

            Route::post('/settings', function (\Illuminate\Http\Request $request) {
                $tenant = \App\Services\TenantContext::get();
                abort_if(!$tenant, 404);

                $validated = $request->validate([
                    'tax_rate' => ['nullable', 'numeric', 'min:0', 'max:1'],
                    'service_rate' => ['nullable', 'numeric', 'min:0', 'max:1'],
                    'stock_deduct_on' => ['nullable', 'in:paid,open'],
                    'qris_static_payload' => ['nullable', 'string'],
                ]);

                $existing = DB::table('tenant_settings')
                    ->where('tenant_id', $tenant->id)
                    ->first();

                $payments = json_decode($existing?->payments_json ?? '{}', true) ?: [];
                $payments['qris_mode'] = $request->boolean('payment_qris_static') ? 'static' : ($payments['qris_mode'] ?? 'snap');
                $payments['qris_snap'] = $payments['qris_snap'] ?? [
                    'client_key' => null,
                    'server_key' => null,
                    'expiry_minutes' => 15,
                ];
                $payments['qris_static'] = [
                    'qr_payload' => $validated['qris_static_payload'] ?? null,
                    'qr_image_url' => $payments['qris_static']['qr_image_url'] ?? null,
                ];
                $payments['cash_enabled'] = $request->boolean('payment_cash');
                $payments['qris_enabled'] = $request->boolean('payment_qris')
                    || $request->boolean('payment_qris_static')
                    || $request->boolean('payment_qris_snap');

                DB::table('tenant_settings')->updateOrInsert(
                    ['tenant_id' => $tenant->id],
                    [
                        'tax_enabled' => $request->boolean('tax_enabled'),
                        'tax_rate' => $validated['tax_rate'] ?? 0,
                        'tax_inclusive' => $request->boolean('tax_inclusive'),
                        'service_enabled' => $request->boolean('service_enabled'),
                        'service_rate' => $validated['service_rate'] ?? 0,
                        'service_inclusive' => $request->boolean('service_inclusive'),
                        'payments_json' => json_encode($payments),
                        'stock_deduct_on' => $validated['stock_deduct_on'] ?? 'paid',
                        'kitchen_ticket_on_open_for_cash' => $request->boolean('kitchen_ticket_on_open_for_cash'),
                        'qris_static_payload' => $validated['qris_static_payload'] ?? null,
                        'updated_at' => now(),
                        'created_at' => $existing?->created_at ?? now(),
                    ]
                );

                return back()->with('success', 'Settings berhasil disimpan.');
            })->name('settings.update');

            Route::get('/roles', function () {
                $tenant = \App\Services\TenantContext::get();
                setPermissionsTeamId($tenant?->id);

                return view('admin.roles.index', [
                    'users' => \App\Models\User::query()
                        ->where('tenant_id', $tenant?->id)
                        ->with('roles')
                        ->latest()
                        ->paginate(15),
                    'roles' => \Spatie\Permission\Models\Role::query()
                        ->where('guard_name', 'web')
                        ->when($tenant, fn ($query) => $query->where('tenant_id', $tenant->id))
                        ->orderBy('name')
                        ->get(),
                ]);
            })->name('roles.index');

            Route::put('/roles/{user}', function (\Illuminate\Http\Request $request, $tenant, $user) {
                $tenantModel = \App\Services\TenantContext::get();
                abort_if(!$tenantModel, 404);

                $staff = \App\Models\User::query()
                    ->where('tenant_id', $tenantModel->id)
                    ->where('id', $user)
                    ->firstOrFail();

                $request->validate([
                    'role' => ['required', 'string', 'max:100'],
                ]);

                setPermissionsTeamId($tenantModel->id);

                $role = \Spatie\Permission\Models\Role::query()
                    ->where('tenant_id', $tenantModel->id)
                    ->where('guard_name', 'web')
                    ->where('name', $request->role)
                    ->firstOrFail();

                $staff->syncRoles([$role]);

                return back()->with('success', 'Role staff berhasil diperbarui.');
            })->name('roles.update');

            Route::get('/menu-costing', function () {
                return view('admin.menu-costing.index');
            })->name('menu-costing.index');

            Route::post('/qris-static/generate', function (\Illuminate\Http\Request $request) {
                $tenant = \App\Services\TenantContext::get();
                abort_if(!$tenant, 404);

                $validated = $request->validate([
                    'amount' => ['required', 'numeric', 'min:1'],
                ]);

                $settings = DB::table('tenant_settings')
                    ->where('tenant_id', $tenant->id)
                    ->first();

                $payments = json_decode($settings?->payments_json ?? '{}', true) ?: [];
                $payload = trim($settings?->qris_static_payload ?: ($payments['qris_static']['qr_payload'] ?? ''));

                if (!$payload) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Payload QRIS static belum disimpan di Settings.',
                    ], 422);
                }

                $svg = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
                    ->size(320)
                    ->margin(2)
                    ->generate($payload);

                return response()->json([
                    'success' => true,
                    'message' => 'QRIS static berhasil dibuat.',
                    'payload' => $payload,
                    'qr_url' => 'data:image/svg+xml;base64,' . base64_encode($svg),
                ]);
            })->name('qris-static.generate');

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

Route::middleware(['auth'])
    ->prefix('{tenant}/admin')
    ->name('tenant.admin.')
    ->group(function () {

        Route::resource('materials', MaterialController::class)
            ->except(['show']);

        Route::get('/recipes', [RecipeController::class, 'index'])
            ->name('recipes.index');

        Route::get('/recipes/create', [RecipeController::class, 'create'])
            ->name('recipes.create');

        Route::post('/recipes', [RecipeController::class, 'store'])
            ->name('recipes.store');

        Route::delete('/recipes/{recipe}', [RecipeController::class, 'destroy'])
            ->name('recipes.destroy');

        Route::post('/materials/{material}/stock-in', [StockMovementController::class, 'stockIn'])
            ->name('materials.stock-in');

        Route::post('/materials/{material}/stock-out', [StockMovementController::class, 'stockOut'])
            ->name('materials.stock-out');

        Route::post('/materials/{material}/adjustment', [StockMovementController::class, 'adjustment'])
            ->name('materials.adjustment');

        Route::get('/stock-movements', [StockMovementController::class, 'index'])
            ->name('stock-movements.index');
    });