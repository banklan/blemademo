<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
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
        $this->middleware('guest')->except(['logout', 'userLogout']);
    }

    // public function login(Request $request)
    // {
    //     $this->validateLogin($request);

    //     if ($this->hasTooManyLoginAttempts($request)) {
    //         $this->fireLockoutEvent($request);

    //         return $this->sendLockoutResponse($request);
    //     }

    //     if(Auth::attempt(['email' => $request->email, 'password' => $request->password])) {

    //         if(Auth::attempt(['status' => 1])){
    //             return redirect()->intended('home');
    //         }else{
    //             $this->incrementLoginAttempts($request);
    //             return response()->json([
    //                 'error' => 'Login failed. Please contact the site admin.'
    //             ], 401);
    //         }
    //     } else {
    //         $this->incrementLoginAttempts($request);
    //         return $this->sendFailedLoginResponse($request);
    //     }
    // }

    public function authenticate(Request $request)
    {
        $attempt = Auth::attempt([
             'email' => $request->email,
             'password' => $request->password,
             'status' => true
        ]);

        if($attempt){
            return redirect()->intended('home');
        }else {
            $this->incrementLoginAttempts($request);
            return $this->sendFailedLoginResponse($request);
        }
    }

    public function userLogout(Request $request)
    {
        Auth::guard()->logout();

        $request->session()->invalidate();

        return redirect('/');
    }
}
