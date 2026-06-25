<?php
namespace App\Http\Controllers\Central;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Http\Request;
use App\Models\Tenant;
use App\Models\TenantSetting;
use App\Models\User;
use App\Models\Plan;
use App\Models\Owner;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SignupController extends Controller
{
    public function checkSlug(Request $r)
    {
        $slug = $r->query('slug', '');
        $slug = strtolower(trim($slug));

        if (!preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $slug) || strlen($slug) < 3) {
            return response()->json(['available' => false, 'suggestions' => []]);
        }

        $reserved = ['www','admin','api','app','support','pricing','signup','login'];
        if (in_array($slug, $reserved, true)) {
            return response()->json(['available' => false, 'suggestions' => [$slug.'-resto', $slug.'-kafe', $slug.'-outlet']]);
        }

        $exists = Tenant::where('slug', $slug)->exists();
        if (!$exists) {
            return response()->json(['available' => true, 'suggestions' => []]);
        }

        $base = $slug;
        $suggestions = [];
        $suffixes = ['-resto', '-kafe', '-id', '-official', '-app', '2', '3'];
        foreach ($suffixes as $suffix) {
            $candidate = $base . $suffix;
            if (!Tenant::where('slug', $candidate)->exists() && count($suggestions) < 3) {
                $suggestions[] = $candidate;
            }
        }

        return response()->json(['available' => false, 'suggestions' => $suggestions]);
    }

    public function show(Request $r)
    {
        $plans = Plan::active()
            ->orderByRaw("FIELD(code, 'pro','starter')")
            ->get(['code','name','price_monthly','price_yearly','features_json']);

        $selected = $r->query('plan');
        if (!$plans->pluck('code')->contains($selected)) {
            $selected = optional($plans->first())->code;
        }

        // Handle Google OAuth pre-fill data
        $googlePrefill = session('google_signup_data');
        if ($googlePrefill) {
            session()->forget('google_signup_data');
        }

        return view('landing.signup', compact('plans', 'selected', 'googlePrefill'));
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'restaurant_name' => ['required','string','max:120'],
            'tenant_slug'     => [
                'required','string','max:60',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::unique('tenants','slug'),
            ],
            'owner_name'  => ['required','string','max:100'],
            'email'       => ['required','email','max:150'],
            'phone'       => ['required','string','max:30'],
            'password'    => ['required','string','min:8','max:100'],
            'plan'        => ['required', Rule::exists('plans','code')->where('is_active', true)],
        ], [
            'tenant_slug.regex' => 'Slug hanya boleh huruf kecil, angka, dan tanda minus (-).',
        ]);

        $reserved = ['www','admin','api','app','support','pricing','signup','login'];
        if (in_array($data['tenant_slug'], $reserved, true)) {
            throw ValidationException::withMessages([
                'tenant_slug' => 'Slug tersebut tidak tersedia. Silakan pilih yang lain.',
            ]);
        }

        if ($data['plan'] === 'enterprise') {
            return back()
                ->withErrors(['plan' => 'Paket Enterprise perlu proses demo & kontrak. Silakan hubungi kami.'])
                ->withInput();
        }

        $planId = null;
        if (class_exists(Plan::class)) {
            $plan = Plan::where('code', $data['plan'])->first();
            if ($plan) $planId = $plan->id;
        }

        $now       = now();
        $trialEnds = $now->copy()->addDays(7);

        [$tenant, $owner] = DB::transaction(function () use ($data, $trialEnds, $planId) {

            $tenant = Tenant::create([
                'name'          => $data['restaurant_name'],
                'slug'          => $data['tenant_slug'],
                'plan_id'       => $planId,
                'status'        => 'trialing',
                'trial_ends_at' => $trialEnds,
            ]);

            $outlet = \App\Models\Outlet::create([
                'tenant_id'  => $tenant->id,
                'name'       => 'Outlet Utama',
                'address'    => null,
                'is_default' => 1,
            ]);

            if (class_exists(PermissionRegistrar::class)) {
                app(PermissionRegistrar::class)->setPermissionsTeamId($tenant->id);
            }

            $exists = User::where('tenant_id', $tenant->id)
                ->where('email', $data['email'])
                ->exists();
            if ($exists) {
                throw ValidationException::withMessages([
                    'email' => 'Email sudah terdaftar pada tenant ini.',
                ]);
            }

            $owner = User::create([
                'tenant_id' => $tenant->id,
                'name'      => $data['owner_name'],
                'email'     => $data['email'],
                'password'  => Hash::make($data['password']),
                'phone'     => $data['phone'] ?? null,
            ]);

            if (class_exists(Role::class) && method_exists($owner, 'assignRole')) {
                $allPermissions = [
                    'dashboard.view','pos.access','orders.view','orders.pay','orders.void',
                    'kitchen.access','inventory.view','inventory.manage','recipe.manage',
                    'costing.view','stock.transfer','waste.manage','stock.movement.view',
                    'outlet.manage','menu.manage','users.manage','reports.view',
                ];

                foreach ($allPermissions as $perm) {
                    \Spatie\Permission\Models\Permission::firstOrCreate([
                        'name'       => $perm,
                        'guard_name' => 'web',
                        'tenant_id'  => $tenant->id,
                    ]);
                }

                $ownerRole = Role::firstOrCreate(['name' => 'owner',   'guard_name' => 'web', 'tenant_id' => $tenant->id]);
                Role::firstOrCreate(['name' => 'manager',  'guard_name' => 'web', 'tenant_id' => $tenant->id]);
                Role::firstOrCreate(['name' => 'cashier',  'guard_name' => 'web', 'tenant_id' => $tenant->id]);
                Role::firstOrCreate(['name' => 'kitchen',  'guard_name' => 'web', 'tenant_id' => $tenant->id]);
                Role::firstOrCreate(['name' => 'waiter',   'guard_name' => 'web', 'tenant_id' => $tenant->id]);

                $permissionObjects = \Spatie\Permission\Models\Permission::where('guard_name', 'web')
                    ->where('tenant_id', $tenant->id)
                    ->whereIn('name', $allPermissions)
                    ->get();

                app(PermissionRegistrar::class)->forgetCachedPermissions();
                app(PermissionRegistrar::class)->setPermissionsTeamId($tenant->id);

                $ownerRole->permissions()->detach();
                $permissionObjects->each(function ($perm) use ($ownerRole, $tenant) {
                    DB::table('role_has_permissions')->insert([
                        'permission_id' => $perm->id,
                        'role_id'       => $ownerRole->id,
                        'tenant_id'     => $tenant->id,
                    ]);
                });
                app(PermissionRegistrar::class)->forgetCachedPermissions();

                $owner->assignRole('owner');
            }

            TenantSetting::firstOrCreate(
                ['tenant_id' => $tenant->id],
                [
                    'tax_enabled'   => true,
                    'tax_rate'      => 0.11,
                    'tax_inclusive' => false,
                    'service_enabled'   => true,
                    'service_rate'      => 0.05,
                    'service_inclusive' => false,
                    'kitchen_ticket_on_open_for_cash' => true,
                    'stock_deduct_on' => 'paid',
                    'payments_json' => [
                        'cash_enabled' => true,
                        'qris_enabled' => true,
                        'qris_mode'    => 'snap',
                        'qris_snap'    => ['server_key'=>null,'client_key'=>null,'expiry_minutes'=>15],
                        'qris_static'  => ['qr_payload'=>null,'qr_image_url'=>null],
                    ],
                ]
            );

            $tenant->owner_user_id = $owner->id;
            $tenant->save();

            return [$tenant, $owner, $outlet];
        });

        $tenantUrl = url("/{$tenant->slug}/login");
        return redirect()->away($tenantUrl)
            ->with('ok', "Tenant {$tenant->slug} dibuat. Trial s/d {$trialEnds->format('Y-m-d')}");
    }

    /**
     * Redirect ke Google OAuth
     */
    public function googleRedirect()
    {
        $plan = request('plan', 'starter');
        session(['google_signup_plan' => $plan]);

        return \Laravel\Socialite\Facades\Socialite::driver('google')->redirect();
    }

    /**
     * Handle callback dari Google OAuth
     */
    public function googleCallback()
    {
        $googleUser = \Laravel\Socialite\Facades\Socialite::driver('google')->user();

        $plan = session('google_signup_plan', 'starter');
        session()->forget('google_signup_plan');

        $existingUser = User::where('email', $googleUser->getEmail())->first();

        if ($existingUser) {
            auth()->login($existingUser);
            return redirect()->away(url("/{$existingUser->tenant->slug}/login"));
        }

        session([
            'google_signup_data' => [
                'name'  => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'avatar'=> $googleUser->getAvatar(),
                'plan'  => $plan,
            ]
        ]);

        return redirect()->route('central.signup', ['plan' => $plan]);
    }
}
