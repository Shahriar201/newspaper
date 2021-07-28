<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Laravel\Socialite\Facades\Socialite;
use App\User;
use Auth;

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
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function redirectToProvider($website)
    {
        return Socialite::driver($website)->redirect();
    }

    public function handleProviderCallback($website)
    {
        if ($website == 'google') {   
            $user = Socialite::driver($website)->user();
        }else {
            $user = Socialite::driver($website)->stateless()->user();
        }
        // login if user in the database
        $user_found = User::where('email', $user->getEmail())->first();
        if ($user_found) {
            Auth::login($user_found);
            return redirect('/');
        }else {
            # make a new user
            $new_user = new User();
            $new_user->name = $user->getName();
            $new_user->email = $user->getEmail();
            $new_user->password = bcrypt(123456);
            // dd($new_user);
            if ($new_user->save()) {
                Auth::login($new_user);
                return redirect('/home');
            }
        }
        // return redirect()->route('home');
        // return $user->getName();
        // dd($user);
    }
}
