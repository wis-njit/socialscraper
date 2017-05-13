<?php

namespace app\Services;

use App\ProviderUserProfile;
use DB;
use Laravel\Socialite\AbstractUser;
use app\User;

/**This service is utilized throughout the application and facilitates functions relevant
 * to current users and their data
 *
 * Class UserService
 * @package app\Services
 */
class UserService
{
    public function __construct()
    {
    }


    /**Retrieve all the providers' name for which the user has an account with
     *
     * @param string $userId
     * @return \Illuminate\Support\Collection
     */
    public function getAllProviderAccounts(string $userId){
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

    /**Disassociate(unlink) the provider from the user's account via a soft delete
     *
     * @param string $oauthProvider
     * @param string $userId
     * @return bool|mixed|null
     */
    public function disassociateProviderAccount(string $oauthProvider, string $userId){
        return ProviderUserProfile::whereHas('providerName' , function($query) use ($oauthProvider){
            $query->where('name', $oauthProvider);
        })
            ->where('user_id', $userId)
            ->delete();
    }

    /**Returns the ProviderUserProfile given a user id
     *
     * @param string $userId
     * @param string $oauthProvider
     * @return ProviderUserProfile|mixed|null
     */
    public function getUserProviderProfile(string $userId, string $oauthProvider){
        return ProviderUserProfile::whereHas('providerName' , function($query) use ($oauthProvider){
            $query->where('name', $oauthProvider);
        })
            ->where('user_id', $userId)
            ->first();
    }

    /**Returns the ProviderUserProfile given a provider's user id
     *
     * @param string $pid
     * @param string $oauthProvider
     * @return ProviderUserProfile|mixed|null
     */
    public function getUserProviderProfileByProviderId(string $pid, string $oauthProvider){
        return ProviderUserProfile::whereHas('providerName' , function($query) use ($oauthProvider){
            $query->where('name', $oauthProvider);
        })
            ->where('provider_user_id', $pid)
            ->first();
    }

    //TODO add helper function for current user
    /**Update the OAuth access token for the user's provider account
     *
     * @param AbstractUser $user
     * @param string $oauthProvider
     */
    public function updateUserSNSToken(AbstractUser $user, User $authUser, string $oauthProvider){

        $profile = $this->getUserProviderProfileByProviderId($user->getId(), $oauthProvider);
        $profile->access_token = $user->token;
        $profile->access_token_key = isset($user->tokenSecret) ? $user->tokenSecret : '';
        $profile->save();
    }

    /**Add the provider the user logged in with to the session
     *
     * @param string $oauthProvider
     */
    public function setCurrentSNSProvider(string $oauthProvider){
        session(['currentProvider' => $oauthProvider]);
    }

    /**Retrieve the provider that the user is currently logged in with
     *
     * @return mixed
     */
    public function getCurrentSNSProvider(){
        return session('currentProvider');
    }

}