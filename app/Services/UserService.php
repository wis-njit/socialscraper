<?php

namespace app\Services;

use DB;

class UserService
{
    public function __construct()
    {
    }


    /**
     *Retrieve all the providers' name for which the user has an account with
     */
    public function getAllProviderAccounts($userId){
          return \DB::table('providers as p')
            ->select('name',
                    DB::raw('(CASE WHEN exists(
                                SELECT provider_type_id
                                FROM provider_user_profiles pu
                                WHERE p.id = pu.provider_type_id 
                                AND pu.user_id = ?) 
                             THEN 1 ELSE 0 END) as active'
                )
            )->addBinding($userId, 'select')
            ->get();

    }

}