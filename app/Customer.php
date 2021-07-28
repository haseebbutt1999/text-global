<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    public function addressess()
    {
        return $this->hasMany(Address::class, 'shopify_customer_id','shopify_customer_id');
    }
    public function orders()
    {
        return $this->hasMany(Address::class, 'shopify_customer_id','customer_id');
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

//    public function getCustomerNameAttribute(){
//        $subject = $template->subject;
//        $body = $template->body;
//        $subject = str_replace('{user}',$user->name,$subject);
//        $body = str_replace('{user}',$user->name,$body);
//    }

}
