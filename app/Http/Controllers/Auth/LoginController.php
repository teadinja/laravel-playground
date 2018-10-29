<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Redirect the user to the Facebook or Google authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from Facebook or Google.
     *
     * @return \Illuminate\Http\Response
     */
    public function callback($provider)
    {
        $provider_user = Socialite::driver($provider)->user();

        $user = User::firstOrCreate([
            'provider' => $provider,
            'provider_user_id' => $provider_user->getId()
        ],
        [
            'name' => $provider_user->getName(),
            'email' => $provider_user->getEmail(),
        ]);

        auth()->login($user, true);
        return redirect()->to('/home');
    }
}
