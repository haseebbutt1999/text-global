<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    public function users()
    {
        return $this->belongsToMany(User::class, 'country_users');
    }

    public function users_country_pref()
    {
        return $this->belongsToMany(User::class, 'country_shoppreferences');
    }
}
