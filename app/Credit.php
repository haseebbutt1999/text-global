<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Credit extends Model
{
    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}
