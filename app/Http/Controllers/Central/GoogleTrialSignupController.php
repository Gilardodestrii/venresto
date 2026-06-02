<?php

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Tenant;
use App\Models\TenantSetting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Facades\Socialite;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class GoogleTrialSignupController extends Controller
{
    public function redirect(Request $request)
    {
        if ($request->filled('plan')) {
            session(['google_signup_plan' => $request->query('plan')]);
        }

        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
        } catch (\Throwable $e) {
            return redirect('/signup')->withErrors([
                'google' => 'Login Google gagal. Silakan coba lagi atau gunakan daftar manual.',
            ]);
        }

        session([
            'google_signup' => [
                'id' => $googleUser->getId(),
                'name' => $googleUser->getName() ?: $googleUser->getNickname(),
                'email' => $googleUser->getEmail(),
                'avatar' => $googleUser->getAvatar(),
            ],
        ]);

        return redirect()->route('signup.google.complete');
    }

    public function complete(Request $request)
    {
        $google = session('google_signup');

        if (!$google || empty($google['email']) || empty($google['id'])) {
            return redirect('/signup')->withErrors([
                'google' => 'Sesi Google tidak ditemukan. Silakan daftar dengan Google kembali.',
            ]);
        }

        $plans = Plan::active()
            ->orderByRaw("FIELD(code, 'pro','starter')")
            ->get(['code', 'name', 'price_monthly', 'price_yearly', 'features_json']);

        $selected = old('plan', session('google_signup_plan'));
        if (!$plans->pluck('code')->contains($selected)) {
            $selected = optional($plans->first())->code;
        }

        return view('landing.signup-google-complete', compact('google', 'plans', 'selected'));
    }

    public function store(Request $request)
    {
        $google = session('google_signup');

        if (!$google || empty($google['email']) || empty($google['id'])) {
            return redirect('/signup')->withErrors([
                'google' => 'Sesi Google tidak ditemukan. Silakan daftar dengan Google kembali.',
            ]);
        }

        $data = $request->validate([
            'restaurant_name' => ['required', 'string', 'max:120'],
            'tenant_slug' => [
                'required',
                'string',
                'max:60',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::unique('tenants', 'slug'),
            ],
            'phone' => ['required', 'string', 'max:30'],
            'plan' => ['required', Rule::exists('plans', 'code')->where('is_active', true)],
            'agree' => ['accepted'],
        ], [
            'tenant_slug.regex' => 'Slug hanya boleh huruf kecil, angka, dan tanda minus (-).',
            'agree.accepted' => 'Anda harus menyetujui Syarat Layanan dan Kebijakan Privasi.',
        ]);

        $reserved = ['www', 'admin', 'api', 'app', 'support', 'pricing', 'signup', 'login'];
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

        $plan = Plan::where('code', $data['plan'])->first();
        $trialEnds = now()->copy()->addDays(7);

        [$tenant, $owner] = DB::transaction(function () use ($data, $google, $plan, $trialEnds) {
            $tenant = Tenant::create([
                'name' => $data['restaurant_name'],
                'slug' => $data['tenant_slug'],
                'plan_id' => $plan?->id,
                'status' => 'trialing',
                'trial_ends_at' => $trialEnds,
            ]);

            \App\Models\Outlet::create([
                'tenant_id' => $tenant->id,
                'name' => 'Outlet Utama',
                'address' => null,
                'is_default' => 1,
            ]);

            if (class_exists(PermissionRegistrar::class)) {
                app(PermissionRegistrar::class)->setPermissionsTeamId($tenant->id);
            }

            $owner = User::create([
                'tenant_id' => $tenant->id,
                'name' => $google['name'] ?: Str::before($google['email'], '@'),
                'email' => $google['email'],
                'phone' => $data['phone'],
                'password' => Hash::make(Str::random(48)),
                'provider' => 'google',
                'google_id' => $google['id'],
                'avatar' => $google['avatar'] ?? null,
                'email_verified_at' => now(),
            ]);

            if (class_exists(Role::class) && method_exists($owner, 'assignRole')) {
                Role::firstOrCreate(['name' => 'owner', 'guard_name' => 'web', 'tenant_id' => $tenant->id]);
                Role::firstOrCreate(['name' => 'manager', 'guard_name' => 'web', 'tenant_id' => $tenant->id]);
                Role::firstOrCreate(['name' => 'cashier', 'guard_name' => 'web', 'tenant_id' => $tenant->id]);
                Role::firstOrCreate(['name' => 'kitchen', 'guard_name' => 'web', 'tenant_id' => $tenant->id]);
                Role::firstOrCreate(['name' => 'waiter', 'guard_name' => 'web', 'tenant_id' => $tenant->id]);

                $owner->assignRole('owner');
            }

            TenantSetting::firstOrCreate(
                ['tenant_id' => $tenant->id],
                [
                    'tax_enabled' => true,
                    'tax_rate' => 0.11,
                    'tax_inclusive' => false,
                    'service_enabled' => true,
                    'service_rate' => 0.05,
                    'service_inclusive' => false,
                    'kitchen_ticket_on_open_for_cash' => true,
                    'stock_deduct_on' => 'paid',
                    'payments_json' => [
                        'cash_enabled' => true,
                        'qris_enabled' => true,
                        'qris_mode' => 'snap',
                        'qris_snap' => ['server_key' => null, 'client_key' => null, 'expiry_minutes' => 15],
                        'qris_static' => ['qr_payload' => null, 'qr_image_url' => null],
                    ],
                ]
            );

            $tenant->owner_user_id = $owner->id;
            $tenant->save();

            return [$tenant, $owner];
        });

        session()->forget(['google_signup', 'google_signup_plan']);

        Auth::login($owner);

        return redirect("/{$tenant->slug}/admin/dashboard")
            ->with('success', "Tenant {$tenant->slug} berhasil dibuat. Trial aktif sampai {$trialEnds->format('Y-m-d')}.");
    }
}
