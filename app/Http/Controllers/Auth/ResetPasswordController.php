<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Ninthspace\Dweller\Tenant\Tenant;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    public function showResetForm(Request $request, $token = null)
    {
        $slug   = null;
        $tenant = Tenant::find($request->tenant_id);
        if ($tenant) {
            $slug = $tenant->name();
        }

        return view('auth.passwords.reset')->with(
            ['token' => $token, 'email' => $request->email, 'slug' => $slug]
        );
    }

    /**
     * Get the password reset credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        $credentials = $request->only(
            'email',
            'password',
            'password_confirmation',
            'token'
        );

        $tenant = Tenant::fromParams($request->all());

        $credentials['tenant_id'] = $tenant->id;

        return $credentials;
    }
}
