<?php

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Outlet;
use App\Services\TenantContext;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function show()
    {
        $tenant = TenantContext::get();
        $outlet = null;

        if ($tenant) {
            $outlet = Outlet::firstOrCreate(
                ['tenant_id' => $tenant->id],
                ['name' => 'Outlet Utama']
            );

            session([
                'current_outlet_id' => $outlet->id,
            ]);
        }

        return view('auth.login', [
            'currentTenant' => $tenant,
            'currentOutlet' => $outlet,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'remember' => ['nullable', 'boolean'],
        ]);

        $tenant = TenantContext::get();

        $user = User::query()
            ->with('tenant')
            ->when($tenant, fn ($query) => $query->where('tenant_id', $tenant->id))
            ->where('email', $request->email)
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => 'Kredensial tidak valid.',
            ]);
        }

        $resolvedTenant = $tenant ?: $user->tenant;

        if (!$resolvedTenant) {
            throw ValidationException::withMessages([
                'email' => 'Akun ini belum terhubung dengan tenant.',
            ]);
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        TenantContext::set($resolvedTenant);

        $outlet = Outlet::firstOrCreate(
            ['tenant_id' => $resolvedTenant->id],
            ['name' => 'Outlet Utama']
        );

        session([
            'current_outlet_id' => $outlet->id,
        ]);

        if (method_exists($user, 'hasRole') && ($user->hasRole('owner') || $user->hasRole('manager'))) {
            return redirect()->intended(url($resolvedTenant->slug . '/admin/dashboard'));
        }

        return redirect()->intended(url($resolvedTenant->slug . '/admin/pos'));
    }

    public function destroy(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('status', 'Anda telah keluar.');
    }
}
