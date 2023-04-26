<?php

namespace App\Http\Controllers\SystemAdmin;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SystemAdminAuthController extends Controller
{
    public function loginView()
    {
        return view('systemAdmin.auth.login');
    }
    public function loginStore(Request $request)
    {
        $remember_me = $request->input('remember_me');
        $credentials = $request->only('email', 'password');
        $credentials["role"] = 2;
        if (Auth::guard('admin')->attempt($credentials, $remember_me)) {
            Auth::guard('admin')->login(Auth::guard('admin')->user(), $remember_me);
            $user = Auth::guard('admin')->user();
            return response()->json([
                "status" => true,
                "message" => "Hoşgeldiniz $user->name Panele Yönlendiriliyosunuz.",
                "url" => route('systemAdmin.index')
            ]);
        } else {
            return response()->json([
                "status" => false,
                "type" => "danger",
                "message" => "Bu bilgilere göre kayıt bulunamadı.Lütfen Bilgilerinizi kontrol edip tekrar deneyin."
            ]);
        }
    }
    public function logout()
    {
        if (Auth::guard('admin')->logout()) {
            return redirect()->route('systemAdmin.login');
        }
    }
}
