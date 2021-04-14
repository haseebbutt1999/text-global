<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe;

class StripeController extends Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function stripePost(Request $request)
    {
//        dd($request->all());
        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        Stripe\Charge::create ([
            "amount" => $request->price * 100,
            "currency" => "usd",
            "source" => $request->stripeToken,
            "description" => "Test payment from tutsmake.com."
        ]);

        $user = User::find(Auth::user()->id);
        $user->credit += $request->credits;
        $user->save();

        return redirect()->back();
    }
}
