<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{

    function providerUserProfile(){
        return $this->belongsTo(ProviderUserProfile::class, 'provider_type_id');
    }
}
