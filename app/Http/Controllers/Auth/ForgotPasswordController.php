<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Ninthspace\Dweller\Tenant\Tenant;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Get the needed authentication credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        $tenant = Tenant::fromParams($request->all());

        $tenantId = null;
        if ($tenant) {
            $tenantId = $tenant->id;
        }

        return [
            'email'     => $request->get('email'),
            'tenant_id' => $tenantId,
        ];
    }

    /**
     * Get the response for a failed password reset link.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
        return back()
            ->withInput($request->only('email', 'slug'))
            ->withErrors(['email' => trans($response)]);
    }
}
