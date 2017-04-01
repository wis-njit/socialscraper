<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Provider;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\ProviderUserProfile
 *
 * @mixin \Eloquent
 */
class ProviderUserProfile extends Model
{
    use SoftDeletes;

    function providerName(){
        return $this->hasOne(Provider::class, 'id', 'provider_type_id');
    }

    function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
