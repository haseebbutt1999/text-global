<?php

namespace App;

use App\Http\Controllers\LogsController;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Illuminate\Support\Facades\Auth;
use Osiset\ShopifyApp\Contracts\ShopModel as IShopModel;
use Osiset\ShopifyApp\Storage\Models\Plan;
use Osiset\ShopifyApp\Traits\ShopModel;

class User extends Authenticatable implements IShopModel
{
    use Notifiable, ShopModel;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $log_store;

//    public function __construct()
//    {
//        $this->log_store = new LogsController();
//    }
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function shopdetail()
    {
        return $this->hasOne('App\Shopdetail');
    }

    public function countries()
    {
        return $this->belongsToMany(Country::class, 'country_users');
    }

    public function country_shop_pref()
    {
        return $this->belongsToMany(Country::class, 'country_shoppreferences');
    }

    public function customers()
    {
        return $this->hasMany(Customer::class,'user_id', 'id');
    }

    public function setPlanIdAttribute($value)
    {
        $this->attributes['plan_id'] = $value;
        if($value != '1'){
            $this->attributes['credit'] += Plan::find($value)->credit;
        }

        $log_store = new LogsController();
        $log_store->log_store(Auth::user()->id, 'Plan', $value, Plan::find($value)->name, 'Plan Updated by User' , $notes = null);

    }
}
