<?php

namespace App\Http\Controllers\Student;

use App\Models\Student;
use App\Models\StudentTeacher;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class StudentAuthController extends Controller
{
    public function loginView()
    {
        return view('student.auth.login');
    }

    public function registerView()
    {
        return view('student.auth.register');
    }

    public function registerStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => [
                'required',
                'email',
                'unique:students'
            ]
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => ucfirst($validator->errors()->first('email'))
            ]);
        }
        try {
            $student = new Student;
            $student->name = $request->input('name');
            $student->email = $request->input('email');
            $student->password = Hash::make($request->input('password'));
            if ($request->input('phone')) {
                $student->phone = $request->input('phone');
            }
            if ($request->input('address')) {
                $student->address = $request->input('address');
            }
            if ($student->saveOrFail()) {
                $credentials = $request->only('email', 'password');
                if (Auth::guard('student')->attempt($credentials)) {
                    Auth::guard('student')->login(Auth::guard('student')->user());
                    $user = Auth::guard('student')->user();
                }
                return response()->json([
                    "status" => true,
                    "message" => "Kayıt İşlemi Başarılı", "url" => route('student.index')
                ]);
            } else {
                return response()->json([
                    "status" => false,
                    "message" => "Ekleme İşlemi Başarısız"
                ]);
            }
        } catch (\Exception $e) {
            return response()->json(["status" => false, "message" => $e->getMessage()]);
        }

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
        Auth::guard('student')->logout();
        return redirect()->route('student.login');
    }
}
