<?php

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Services\TenantContext;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function show()
    {
        $tenant = \App\Services\TenantContext::get();

        abort_unless($tenant, 404, 'Tenant tidak ditemukan');

        $outlet = \App\Models\Outlet::where('tenant_id', $tenant->id)->first();

        // fallback kalau belum ada outlet
        if (!$outlet) {
            $outlet = \App\Models\Outlet::create([
                'tenant_id' => $tenant->id,
                'name'      => 'Outlet Utama',
            ]);
        }

        session([
            'current_outlet_id' => $outlet->id,
        ]);

        return view('auth.login', [
            'currentTenant' => $tenant,
            'currentOutlet'  => $outlet,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'email'    => ['required','email'],
            'password' => ['required','string'],
            'remember' => ['nullable','boolean'],
        ]);

        $tenant = TenantContext::get();
        abort_unless($tenant, 404, 'Tenant tidak ditemukan');

        // Cari user berdasarkan tenant + email
        $user = User::where('tenant_id', $tenant->id)
                    ->where('email', $request->email)
                    ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => 'Kredensial tidak valid.',
            ]);
        }

        Auth::login($user, (bool)$request->boolean('remember'));
        $request->session()->regenerate();

        // Arahkan sesuai role (opsional)
        if (method_exists($user, 'hasRole')) {
            if ($user->hasRole('owner') || $user->hasRole('manager')) {
                return redirect()->intended(tenant_url('admin/dashboard'));
            }
        }
        return redirect()->intended(tenant_url('pos'));
    }

    public function destroy(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect(tenant_url('login'))->with('status', 'Anda telah keluar.');
    }
}
