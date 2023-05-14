<?php

namespace App\Http\Controllers\Student;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentAuthController extends Controller
{
    public function loginView()
    {
        return view('student.auth.login');
    }
    public function loginStore(Request $request)
    {
        $remember_me = $request->input('remember_me');
        $credentials = $request->only('email', 'password');
        if (Auth::guard('student')->attempt($credentials, $remember_me)) {
            Auth::guard('student')->login(Auth::guard('student')->user(), $remember_me);
            $user = Auth::guard('student')->user();
            return response()->json([
                "status" => true,
                "message" => "Hoşgeldiniz $user->name Panele Yönlendiriliyosunuz.",
                "url" => route('student.index')
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
        if (Auth::guard('student')->logout()) {
            return redirect()->route('student.login');
        }
    }
}
