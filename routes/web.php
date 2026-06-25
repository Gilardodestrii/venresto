<?php

use App\Http\Controllers\Api\KitchenController;
use App\Http\Controllers\Api\MenuController;
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
use App\Http\Controllers\Central\MenuCostingController;
use App\Http\Controllers\Central\RecipeController;
use App\Http\Controllers\Central\ReceiptController;
use App\Http\Controllers\Central\StockMovementController;
use App\Http\Controllers\Central\StockTransferController;
use App\Http\Controllers\Central\TenantSettingController;
use App\Http\Controllers\Central\WasteRecordController;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\QrMenuController;

// Route::get('/env-check', function () {
//     return [
//         'host' => env('DB_HOST'),
//         'port' => env('DB_PORT'),
//         'database' => env('DB_DATABASE'),
//         'username' => env('DB_USERNAME'),
//     ];
// });

// Route::get('/db-check', function () {
//     try {
//         DB::connection()->getPdo();

//         return [
//             'success' => true,
//             'db' => DB::connection()->getDatabaseName()
//         ];
//     } catch (\Throwable $e) {
//         return [
//             'error' => $e->getMessage()
//         ];
//     }
// });

Route::middleware('guest')->group(function () {

    // Central login
    Route::get('/login', [LoginController::class, 'show'])
        ->name('central.login');

    Route::post('/login', [LoginController::class, 'store'])
        ->middleware('throttle:login');

    // Tenant login (legacy support)
    Route::get('/{tenant}/login', [LoginController::class, 'show'])
        ->name('tenant.login');

    Route::post('/{tenant}/login', [LoginController::class, 'store'])
        ->middleware('throttle:login');
});

Route::get('/signup', [SignupController::class,'show'])->name('central.signup');
Route::post('/signup', [SignupController::class,'store'])->name('central.signup.store');
Route::get('/signup/check-slug', [SignupController::class,'checkSlug'])->name('central.signup.check-slug');
Route::get('/signup/google', [SignupController::class,'googleRedirect'])->name('central.signup.google');
Route::get('/signup/google/callback', [SignupController::class,'googleCallback'])->name('central.signup.google.callback');

Route::middleware(['auth'])
    ->prefix('{tenant}/admin')
    ->name('tenant.admin.')
    ->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Central\DashboardController::class, 'index'])
            ->name('dashboard');
    });

Route::post('/logout', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

// Tenant switcher - for superadmin switching between tenants
Route::post('/switch-tenant/{tenant}', function ($tenant) {
    $user = auth()->user();
    if ($user->hasRole('superadmin')) {
        session(['tenant_slug' => $tenant]);
        return redirect()->route('tenant.admin.dashboard', $tenant);
    }
    return back()->with('error', 'Unauthorized');
})->middleware('auth')->name('switch.tenant');

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


Route::post('/webhooks/midtrans', MidtransWebhookController::class);

Route::prefix('{tenant}/qr')->group(function () {
    Route::get('/outlets/{outlet}/tables/{table}', [QrMenuController::class, 'index'])
        ->name('qr.menu');

    Route::post('/outlets/{outlet}/order', [QrMenuController::class, 'store'])
        ->name('qr.order.store');

    Route::get('/outlets/{outlet}/tables/{table}/svg', [QrMenuController::class, 'tableQr'])
        ->name('qr.table.svg');
});

// QR Admin routes - separate group WITHOUT tenant.admin. prefix to keep admin.qr.* names
Route::middleware(['auth'])->group(function () {

    Route::get('{tenant}/admin/outlets/{outlet}/qr', [QrAdminController::class, 'index'])
        ->name('admin.qr.index');

    Route::get('{tenant}/admin/outlets/{outlet}/qr/generate/{table}', [QrAdminController::class, 'generate'])
        ->name('admin.qr.generate');

    Route::get('{tenant}/admin/outlets/{outlet}/qr/download/{table}', [QrAdminController::class, 'download'])
        ->name('admin.qr.download');

    Route::post('{tenant}/admin/outlets/{outlet}/qr/store', [QrAdminController::class, 'store'])
        ->name('admin.qr.store');

    Route::delete('{tenant}/admin/outlets/{outlet}/qr/{table}', [QrAdminController::class, 'destroy'])
        ->name('admin.qr.destroy');

});

Route::middleware(['auth'])
    ->prefix('{tenant}/admin')
    ->name('tenant.admin.')
    ->group(function () {

        // Resource routes (automatically get tenant.admin. prefix from parent group)
        Route::resource('outlets', OutletController::class);

        Route::resource('menu-categories', MenuCategoryController::class);

        Route::resource('menu-items', MenuItemController::class);

        Route::resource('materials', MaterialController::class)
            ->except(['show']);

        // Recipe routes
        Route::get('/recipes', [RecipeController::class, 'index'])
            ->name('recipes.index');
        Route::get('/recipes/create', [RecipeController::class, 'create'])
            ->name('recipes.create');
        Route::post('/recipes', [RecipeController::class, 'store'])
            ->name('recipes.store');
        Route::get('/recipes/{recipe}/edit', [RecipeController::class, 'edit'])
            ->name('recipes.edit');
        Route::put('/recipes/{recipe}', [RecipeController::class, 'update'])
            ->name('recipes.update');
        Route::delete('/recipes/{recipe}', [RecipeController::class, 'destroy'])
            ->name('recipes.destroy');

        Route::get('/reports/inventory', function () {
            return view('admin.reports.inventory');
        })->name('reports.inventory');

        Route::get('/settings', [TenantSettingController::class, 'index'])
            ->name('settings.index');

        Route::post('/settings', [TenantSettingController::class, 'update'])
            ->name('settings.update');

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

        Route::get('/roles/{user}/edit', function ($tenant, $user) {
            $tenantModel = \App\Services\TenantContext::get();
            abort_if(!$tenantModel, 404);

            $user = \App\Models\User::query()
                ->where('tenant_id', $tenantModel->id)
                ->where('id', $user)
                ->firstOrFail();

            setPermissionsTeamId($tenantModel->id);

            return view('admin.roles.edit', [
                'user'  => $user,
                'roles' => \Spatie\Permission\Models\Role::query()
                    ->where('guard_name', 'web')
                    ->where('tenant_id', $tenantModel->id)
                    ->where('name', '!=', 'owner')
                    ->orderBy('name')
                    ->get(),
            ]);
        })->name('roles.edit');

        Route::put('/roles/{user}', function (\Illuminate\Http\Request $request, $tenant, $user) {
            $tenantModel = \App\Services\TenantContext::get();
            abort_if(!$tenantModel, 404);

            $staff = \App\Models\User::query()
                ->where('tenant_id', $tenantModel->id)
                ->where('id', $user)
                ->firstOrFail();

            $validated = $request->validate([
                'name'     => ['required', 'string', 'max:255'],
                'email'    => ['required', 'email', 'max:255'],
                'password' => ['nullable', 'string', 'min:6'],
                'role'     => ['required', 'string', 'exists:roles,name'],
            ]);

            setPermissionsTeamId($tenantModel->id);

            $updateData = [
                'name'  => $validated['name'],
                'email' => $validated['email'],
            ];

            if (!empty($validated['password'])) {
                $updateData['password'] = \Illuminate\Support\Facades\Hash::make($validated['password']);
            }

            $staff->update($updateData);

            $staff->syncRoles([$validated['role']]);

            return back()->with('success', 'Staff berhasil diperbarui.');
        })->name('roles.update');

        Route::get('/roles/create', function () {
            $tenant = \App\Services\TenantContext::get();
            setPermissionsTeamId($tenant?->id);

            return view('admin.roles.create', [
                'roles' => \Spatie\Permission\Models\Role::query()
                    ->where('guard_name', 'web')
                    ->where('tenant_id', $tenant->id)
                    ->where('name', '!=', 'owner')
                    ->orderBy('name')
                    ->get(),
            ]);
        })->name('roles.create');

        Route::post('/roles/create', function (\Illuminate\Http\Request $request, $tenant) {
            $tenantModel = \App\Services\TenantContext::get();
            abort_if(!$tenantModel, 404);

            $validated = $request->validate([
                'name'     => ['required', 'string', 'max:255'],
                'email'    => ['required', 'email', 'max:255', 'unique:users,email,NULL,id,tenant_id,' . $tenantModel->id],
                'password' => ['required', 'string', 'min:6'],
                'role'     => ['required', 'string', 'exists:roles,name'],
            ]);

            \Spatie\Permission\PermissionRegistrar::setPermissionsTeamId($tenantModel->id);

            $user = \App\Models\User::create([
                'tenant_id' => $tenantModel->id,
                'name'      => $validated['name'],
                'email'     => $validated['email'],
                'password'  => \Illuminate\Support\Facades\Hash::make($validated['password']),
            ]);

            $user->assignRole($validated['role']);

            return back()->with('success', 'Staff berhasil ditambahkan.');
        })->name('roles.create');

        Route::post('/roles/{user}/delete', function (\Illuminate\Http\Request $request, $tenant, $user) {
            $tenantModel = \App\Services\TenantContext::get();
            abort_if(!$tenantModel, 404);

            $staff = \App\Models\User::query()
                ->where('tenant_id', $tenantModel->id)
                ->where('id', $user)
                ->where('id', '!=', auth()->id())
                ->firstOrFail();

            $staff->delete();

            return back()->with('success', 'Staff berhasil dihapus.');
        })->name('roles.delete');

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

            $payload = \App\Support\QrisStatic::withAmount(
                $payload,
                (int) round((float) $validated['amount'])
            );

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

        Route::get('/menu-costing', [MenuCostingController::class, 'index'])
            ->name('menu-costing.index');

        Route::post('/pos/store', [PosController::class, 'store'])
            ->name('pos.store');

        Route::get('/orders', [OrderController::class, 'index'])
            ->name('orders.index');

        Route::get('/orders/{order}', [OrderController::class, 'show'])
            ->name('orders.show');
        
        Route::post('/orders/{order}/void', [OrderController::class, 'void'])
            ->name('orders.void');

        Route::post('/orders/{order}/payment', [OrderController::class, 'updatePayment'])
            ->name('orders.updatePayment');

        Route::get('/cashier-sessions', [CashierSessionController::class, 'index'])
            ->name('cashier-sessions.index');

        Route::post('/cashier-sessions/open', [CashierSessionController::class, 'open'])
            ->name('cashier-sessions.open');

        Route::post('/cashier-sessions/{session}/close', [CashierSessionController::class, 'close'])
            ->name('cashier-sessions.close');
        Route::get('/orders/{order}/receipt', [ReceiptController::class, 'show'])->name('orders.receipt');

        // Stock routes
        Route::get('/stock-movements', [StockMovementController::class, 'index'])
            ->name('stock-movements.index');

        Route::get('/stock-transfers', [StockTransferController::class, 'index'])
            ->name('stock-transfers.index');

        Route::get('/stock-transfers/create', [StockTransferController::class, 'create'])
            ->name('stock-transfers.create');

        Route::post('/stock-transfers', [StockTransferController::class, 'store'])
            ->name('stock-transfers.store');

        Route::get('/stock-transfers/{stockTransfer}', [StockTransferController::class, 'show'])
            ->name('stock-transfers.show');

        Route::post('/stock-transfers/{stockTransfer}/complete', [StockTransferController::class, 'complete'])
            ->name('stock-transfers.complete');

        Route::post('/stock-transfers/{stockTransfer}/cancel', [StockTransferController::class, 'cancel'])
            ->name('stock-transfers.cancel');

        Route::get('/waste-records', [WasteRecordController::class, 'index'])
            ->name('waste-records.index');

        Route::get('/waste-records/create', [WasteRecordController::class, 'create'])
            ->name('waste-records.create');

        Route::post('/waste-records', [WasteRecordController::class, 'store'])
            ->name('waste-records.store');

        Route::get('/waste-records/{wasteRecord}', [WasteRecordController::class, 'show'])
            ->name('waste-records.show');
    });

// Kitchen route - separate group with tenant.admin.kitchen. prefix
Route::middleware(['auth'])
    ->prefix('{tenant}/admin/kitchen')
    ->name('tenant.admin.kitchen.')
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