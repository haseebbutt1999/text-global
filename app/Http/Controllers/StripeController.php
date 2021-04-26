<?php

namespace App\Http\Controllers;

use App\Jobs\PaymentInvoiceJob;
use App\Test;
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
        if($user->save()){
            $paymentDetail = ['method'=>'paypal', 'credits'=>$request->credits, 'price'=>$request->price * 100];
            $t = new Test();
            $t->text = json_encode($user->shopdetail->firstname)."stripe:".json_encode($paymentDetail);
            $t->save();
            $paymentInvoiceJob = (new PaymentInvoiceJob($user,$paymentDetail));
            dispatch($paymentInvoiceJob);
        }

        return redirect()->back();
    }
}
