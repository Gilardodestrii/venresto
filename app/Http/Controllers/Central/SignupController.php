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
    public function show(Request $r)
{
    // Ambil hanya plan aktif + kolom yang dibutuhkan
    $plans = Plan::active()
        ->orderByRaw("FIELD(code, 'pro','starter')") // urutan opsional
        ->get(['code','name','price_monthly','price_yearly','features_json']);

    // Preselect dari query ?plan=pro (opsional)
    $selected = $r->query('plan');
    if (!$plans->pluck('code')->contains($selected)) {
        $selected = optional($plans->first())->code; // fallback plan pertama
    }

    return view('landing.signup', compact('plans','selected'));
}


        public function store(Request $r)
        {
            // 1) Validasi awal
            $data = $r->validate([
                'restaurant_name' => ['required','string','max:120'],
                // hanya huruf kecil, angka, minus; tidak boleh underscore
                'tenant_slug'     => [
                    'required','string','max:60',
                    'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                    Rule::unique('tenants','slug'),
                ],
                'owner_name'      => ['required','string','max:100'],
                'email'           => ['required','email','max:150'],
                'phone'           => ['required','string','max:30'],
                'password'        => ['required','string','min:8','max:100'],
                'plan' => [ 'required', Rule::exists('plans','code')->where('is_active', true) ],

            ], [
                'tenant_slug.regex' => 'Slug hanya boleh huruf kecil, angka, dan tanda minus (-).',
            ]);
    
            // 1a) Cek slug terlarang/reserved (opsional)
            $reserved = ['www','admin','api','app','support','pricing','signup','login'];
            if (in_array($data['tenant_slug'], $reserved, true)) {
                throw ValidationException::withMessages([
                    'tenant_slug' => 'Slug tersebut tidak tersedia. Silakan pilih yang lain.',
                ]);
            }
    
            // Tolak enterprise kalau mau via sales
            if ($data['plan'] === 'enterprise') {
                return back()
                ->withErrors(['plan' => 'Paket Enterprise perlu proses demo & kontrak. Silakan hubungi kami.'])
                ->withInput();
            }

            // Resolve planId (tetap)
            $planId = null;
            if (class_exists(Plan::class)) {
                $plan = Plan::where('code', $data['plan'])->first();
                if ($plan) $planId = $plan->id;
            }

            $now       = now();
            $trialEnds = $now->copy()->addDays(7);

            [$tenant, $owner] = DB::transaction(function () use ($data, $trialEnds, $planId) {

                // a) Buat tenant
                $tenant = Tenant::create([
                    'name'          => $data['restaurant_name'],
                    'slug'          => $data['tenant_slug'],
                    'plan_id'       => $planId,
                    'status'        => 'trialing',
                    'trial_ends_at' => $trialEnds,
                ]);

                $outlet = \App\Models\Outlet::create([
                    'tenant_id'   => $tenant->id,
                    'name'        => 'Outlet Utama',
                    'address'     => null,
                    'is_default'  => 1,
                ]);

                // >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
                // (BARU) Set konteks team/tenant untuk Spatie Permission
                if (class_exists(PermissionRegistrar::class)) {
                    app(PermissionRegistrar::class)->setPermissionsTeamId($tenant->id);
                }
                // <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<

                // b) Cek email unik per tenant
                $exists = User::where('tenant_id', $tenant->id)
                            ->where('email', $data['email'])
                            ->exists();
                if ($exists) {
                    throw ValidationException::withMessages([
                        'email' => 'Email sudah terdaftar pada tenant ini.',
                    ]);
                }

                // c) Buat owner user
                $owner = User::create([
                    'tenant_id' => $tenant->id,
                    'name'      => $data['owner_name'],
                    'email'     => $data['email'],
                    'password'  => Hash::make($data['password']),
                    'phone'     => $data['phone'] ?? null,
                ]);

                // d) (BARU) Pastikan role "owner" ada untuk tenant ini, lalu assign
                if (class_exists(Role::class) && method_exists($owner, 'assignRole')) {
                    // per tenant (teams)
                    Role::firstOrCreate(
                        ['name' => 'owner', 'guard_name' => 'web', 'tenant_id' => $tenant->id]
                    );
                    // opsional: siapkan role lain
                    Role::firstOrCreate(['name' => 'manager','guard_name' => 'web','tenant_id' => $tenant->id]);
                    Role::firstOrCreate(['name' => 'cashier','guard_name' => 'web','tenant_id' => $tenant->id]);
                    Role::firstOrCreate(['name' => 'kitchen','guard_name' => 'web','tenant_id' => $tenant->id]);
                    Role::firstOrCreate(['name' => 'waiter','guard_name' => 'web','tenant_id' => $tenant->id]);

                    $owner->assignRole('owner'); // akan ter-scope ke tenant saat ini
                }

                // e) Settings default (tetap)
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

                // f) Back-reference
                $tenant->owner_user_id = $owner->id;
                $tenant->save();

                return [$tenant, $owner, $outlet];
            });

            // Redirect (tetap)
            // $tenantUrl = "https://{$tenant->slug}.appku.com/login";
            $tenantUrl = url("/{$tenant->slug}/login");
            return redirect()->away($tenantUrl)
                ->with('ok', "Tenant {$tenant->slug} dibuat. Trial s/d {$trialEnds->format('Y-m-d')}");
        }
    }
    