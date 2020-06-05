<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Ninthspace\Dweller\Auth\Rules\NotMemberOfTenant;
use Ninthspace\Dweller\Tenant\Tenant;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Providers\RouteServiceProvider;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        // email should be unique amongst
        // tenant_id, which we don't have yet, so use slug if one is given
        // otherwise it doesn't need to be unique, because it'll be unique
        // in new (random) slug

        $validationRules = [
            'name'     => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];

        if ($data['slug']) {
            // if there is a slug, then we must have (any) email address
            $validationRules['email'] = ['required', 'string', 'email', 'max:255'];

            // and the e-mail must not already be associated with that tenant
            $tenant = Tenant::where('slug', '=', $data['slug'])->first();
            if ($tenant) {
                $validationRules['email'][] = new NotMemberOfTenant($tenant);
            }
        } else {
            // if there is no slug, just check e-mail address not already a slug
            // since solo registrations have their e-mail address as their slug
            // (see create, below)
            $validationRules['email'] = ['required', 'string', 'email', 'max:255', 'unique:tenants,slug'];
        }

        return Validator::make(
            $data,
            $validationRules
        );
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $solo = false;
        if (!$data['slug']) {
            $data['slug'] = $data['email'];
            $solo         = true;
        }

        $tenant = Tenant::firstOrCreate(
            [
                'slug'    => $data['slug'],
                'is_solo' => $solo,
            ]
        );

        $tenant->save();

        // adding attributes explicitly to avoid
        // guarded or fillable issues

        $user            = new User();
        $user->name      = $data['name'];
        $user->email     = $data['email'];
        $user->password  = Hash::make($data['password']);
        $user->tenant_id = $tenant->id;
        $user->save();

        return $user;
    }
}
