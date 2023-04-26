<?php

namespace App\Http\Controllers\SystemAdmin;

use App\Mail\ResetPassword;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use \Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class SystemAdminResetPasswordController extends \Illuminate\Routing\Controller
{

    use SendsPasswordResetEmails;
    protected $guard = 'admin';
    /**
     * Constant representing a successfully reset password.
     *
     * @var string
     */
    public const PASSWORD_RESET = 'systemAdmin.resetPasswordForm';
    public function broker()
    {
        return Password::broker('admin');
    }


    public function showResetForm(Request $request, $token = null)
    {
        $guard = $this->guard();
        return view('systemAdmin.auth.resetPassword')->with(
            ['token' => $token, 'guard' => $guard]
        );
    }
    public function showLinkRequestForm()
    {
        return view('systemAdmin.auth.forgotPassword');
    }
}
