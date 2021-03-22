<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    public function addressess()
    {
        return $this->hasMany(Address::class, 'shopify_customer_id','shopify_customer_id');
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
