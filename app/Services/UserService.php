<?php

namespace app\Services;

use App\ProviderUserProfile;
use App\User;
use DB;
use Laravel\Socialite\AbstractUser;

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
                                AND pu.user_id = ?
                                AND pu.deleted_at is null) 
                             THEN 1 ELSE 0 END) as active'
                )
            )->addBinding($userId, 'select')
            ->get();

    }

    //TODO need future ability to disassociate multiple provider accounts
    public function disassociateProviderAccount($oauthProvider, $userId){
        return ProviderUserProfile::whereHas('providerName' , function($query) use ($oauthProvider){
            $query->where('name', $oauthProvider);
        })
            ->where('user_id', $userId)
            ->delete();
    }

    public function getUserProviderProfile($id, $oauthProvider){
        return ProviderUserProfile::whereHas('providerName' , function($query) use ($oauthProvider){
            $query->where('name', $oauthProvider);
        })
            ->where('user_id', $id)
            ->first();
    }

    public function getUserProviderProfileByProviderId($pid, $oauthProvider){
        return ProviderUserProfile::whereHas('providerName' , function($query) use ($oauthProvider){
            $query->where('name', $oauthProvider);
        })
            ->where('provider_user_id', $pid)
            ->first();
    }

    //TODO add helper function for current user
    public function updateUserSNSToken(AbstractUser $user, User $authUser, string $oauthProvider){

        $profile = $this->getUserProviderProfileByProviderId($user->getId(), $oauthProvider);
        $profile->access_token = $user->token;
        $this->updateProviderUserProfile($profile);
    }

    public function updateUser(User $user){
        $user->save();
    }

    public function updateProviderUserProfile(ProviderUserProfile $profile){
        $profile->save();
    }

    public function setCurrentSNSProvider(string $oauthProvider){
        session(['currentProvider' => $oauthProvider]);
    }

    public function getCurrentSNSProvider(){
        return session('currentProvider');
    }

}