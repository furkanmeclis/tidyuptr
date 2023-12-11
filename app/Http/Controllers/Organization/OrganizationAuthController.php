<?php

namespace App\Http\Controllers\Organization;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrganizationAuthController extends Controller
{
    public function loginView()
    {
        return view('organizationAdmin.auth.login');
    }
    public function loginStore(Request $request)
    {
        $remember_me = $request->input('remember_me');
        $credentials = $request->only('email', 'password');
        if (Auth::guard('organization')->attempt($credentials, $remember_me)) {
            Auth::guard('organization')->login(Auth::guard('organization')->user(), $remember_me);
            $user = Auth::guard('organization')->user();
            return response()->json([
                "status" => true,
                "message" => "Hoşgeldiniz $user->name Panele Yönlendiriliyosunuz.",
                "url" => route('organizationAdmin.index')
            ]);
        } else {
            return response()->json([
                "status" => false,
                "message" => "Bu bilgilere göre kayıt bulunamadı.Lütfen Bilgilerinizi kontrol edip tekrar deneyin."
            ]);
        }
    }
    public function logout()
    {
        Auth::guard('organization')->logout();
            return redirect()->route('organizationAdmin.login');

    }
}
