<?php

namespace app\Services;

use App\ProviderUserProfile;
use config\constants\SocialProvidersEnum;
use app\User;

class SMService
{
    /**
     * SMSService constructor.
     */
    public function __construct()
    {
    }

    /**
     * Return user if exists; create and return if doesn't
     *
     * @param $user
     * @param String $oauthProvider The Oauth provider used
     * @return User
     */
    public function findOrCreateUser($user, $oauthProvider)
    {
        //TODO Refactor into COR handlers


        $authUser = $this->findUserAccount($user, $oauthProvider);

        if ($authUser){
            return $authUser;
        }else {
            return $this->createUser($user);
        }

    }

    /**
     * @param $user
     * @return User
     */
    private function createUser($user)
    {

        return User::create([
            'email' => $user->email ? : ' ',
            'password' => bcrypt(bcrypt($user->id)),
            'name' => $user->name,

        ]);
    }

    /**
     * @param $user
     * @return mixed
     */
    private function findUserAccount($user, $oauthProvider)
    {
        //Look for a user with the email address
        $authUser = $this->findUserByLocalProfile($user);
        if(!$authUser) {
            $authUser = $this->findUserByProviderProfile($user, $oauthProvider);
        }
        return $authUser;
    }

    /**
     * @param $user
     * @param $oauthProvider
     * @param $authUser
     * @return mixed
     */
    private function findUserByProviderProfile($user, $oauthProvider)
    {
        //We will not lookup users' provider data by email as we
        //cannot ensure that the provider is ensuring they own that address

        $retUser = ProviderUserProfile::whereHas('providerName' , function($query) use ($oauthProvider){
                $query->where('name', $oauthProvider);
            })
            ->where('provider_user_id', $user->id)
            ->first();

        if($retUser)
            return $retUser->user;
        else
            return null;
    }

    /**
     * @param $user
     * @return mixed
     */
    private function findUserByLocalProfile($user)
    {
        return User::where('email', $user->email)->first();
    }

    private function handleGenericProviderData($user, $oauthProvider){

        //Static Google/FB/Twitter
        if($oauthProvider != SocialProvidersEnum::INSTAGRAM){
            //Capture specific user data
        }
        else if($oauthProvider == SocialProvidersEnum::INSTAGRAM){
            //Capture specific user data
        }
        else{
            throwException("Provider " . $oauthProvider . " not supported");
        }

    }
}