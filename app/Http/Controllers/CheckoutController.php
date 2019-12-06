<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function checkout()
    {
        $year= Carbon::now()->format('y');
        $month = Carbon::now()->format('m');
        $day = Carbon::now()->format('j');
        $rand = substr(sha1(mt_rand()), 17, 6);
        $order = $year.$month.$day.$rand;

        $amount = intval(Session::get('grandTotal'));
        $user = Auth::user()->name;
        $email = Auth::user()->email;
        return view('pages.checkout')->withAmount($amount)->withOrder($order)->withUser($user)->withEmail($email);
    }

    public function checkoutPost(Request $req)
    {
        $amount = $req->amount;

        $user = Auth::user();
        $name = $user->name;
        $email = $user->email;

        Session::put([
            'grandTotal' => $amount,
        ]);

        return response()->json(['message' => 'sent']);
    }

    public function checkoutConfirm()
    {
        return view('pages.checkout_confirm');
    }
}
