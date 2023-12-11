<?php

namespace App\Http\Controllers\Teacher;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherAuthController extends Controller
{
    public function loginView()
    {
        return view('teacher.auth.login');
    }
    public function loginStore(Request $request)
    {
        $remember_me = $request->input('remember_me');
        $credentials = $request->only('email', 'password');
        if (Auth::guard('teacher')->attempt($credentials, $remember_me)) {
            Auth::guard('teacher')->login(Auth::guard('teacher')->user(), $remember_me);
            $user = Auth::guard('teacher')->user();
            return response()->json([
                "status" => true,
                "message" => "Hoşgeldiniz $user->name Panele Yönlendiriliyosunuz.",
                "url" => route('teacher.index')
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
        Auth::guard('teacher')->logout();
        return redirect()->route('teacher.login');
    }
}
