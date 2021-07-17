<?php

namespace App\Http\Controllers;

use App\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    public function index(){
        return view('adminpanel/module/user/contact');
    }
    public function contact_save(Request $request){
        if(Contact::where('user_id', $request->user_id)->exists()){
            $contact = Contact::where('user_id', $request->user_id)->first();
        }else{
            $conatct = new Contact();
        }
        $conatct->user_id = $request->user_id;
        $conatct->name = $request->name;
        $conatct->email = $request->email;
        $conatct->subject = $request->subject;
        $conatct->message = $request->message;
        $conatct->save();
        if(Auth::user()->user_status == "inactive"){
            return view('adminpanel/module/user/setup_page');
        }else{
            return redirect('/');
        }

    }
}
