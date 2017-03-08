<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{

    function providerUserProfile(){
        return $this->belongsTo(ProviderUserProfile::class, 'id','provider_type_id');
    }

    static function getIdFromName($providerName){
        return Provider::where('name', $providerName)->firstOrFail()->id;
    }

}
