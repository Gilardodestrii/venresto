<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Services\TenantContext;
use App\Services\SettingsService;

class AuthController extends Controller
{
    public function register(Request $r){
        $data = $r->validate([
            'name'=>'required|string|max:100',
            'email'=>'required|email',
            'password'=>'required|string|min:6'
        ]);
        $tenant = TenantContext::get();
        $user = User::create([
            'tenant_id'=>$tenant->id,
            'name'=>$data['name'],
            'email'=>$data['email'],
            'password'=>Hash::make($data['password']),
        ]);
        $user->assignRole('owner');
        $token = $user->createToken('pat')->plainTextToken;
        return response()->json(['token'=>$token,'user'=>$user->load('roles')]);
    }

    public function login(Request $r){
        $data = $r->validate(['email'=>'required|email','password'=>'required']);
        $tenant = TenantContext::get();
        $user = User::where('tenant_id',$tenant->id)->where('email',$data['email'])->first();
        if(!$user || !Hash::check($data['password'],$user->password)){
            return response()->json(['message'=>'Invalid credentials'], 401);
        }
        $token = $user->createToken('pat')->plainTextToken;
        return response()->json(['token'=>$token,'user'=>$user->load('roles')]);
    }
    public function me(Request $r, SettingsService $settings)
    {
        $user = $r->user();
        $tenant = TenantContext::get();
        $tenantSettings = $tenant ? $settings->forTenant($tenant->id) : null;
    
        return response()->json([
            'user' => $user->load('roles'),
            'tenant_settings' => $tenantSettings,
        ]);
    }
}
