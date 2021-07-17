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
//        if(Contact::where('user_id', $request->user_id)->exists()){
//            $contact = Contact::where('user_id', $request->user_id)->first();
//        }else{
        $contact = new Contact();
//        }
        $contact->user_id = $request->user_id;
        $contact->name = $request->name;
        $contact->email = $request->email;
        $contact->subject = $request->subject;
        $contact->message = $request->message;
        $contact->save();
        if(Auth::user()->user_status == "inactive"){
            return view('adminpanel/module/user/setup_page');
        }else{
            return redirect('/');
        }

    }
}
