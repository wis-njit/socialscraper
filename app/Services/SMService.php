<?php

namespace app\Services;

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

        if($oauthProvider != SocialProvidersEnum::INSTAGRAM){
            return $this->handleGenericProviderData($user);
        }
        else if($oauthProvider == SocialProvidersEnum::INSTAGRAM){
            return $this->handleInstagramData($user);
        }
        else{
            throwException("Provider " . $oauthProvider . " not supported");
        }

    }
    private function handleGenericProviderData($user){

        //Static Google/FB/Twitter
        $authUser = User::where('email', $user->email)->first();
        if ($authUser){
            return $authUser;
        }else {
            return $this->createUser($user);

        }
    }

    private function handleInstagramData($user){

        $instEmail = $user->id . '@instagram.com';
        $authUser = User::where('email', $instEmail)->first();
        if ($authUser){
            return $authUser;
        }else {
            $user->email = $instEmail;
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
            'email' => $user->email,
            'password' => bcrypt(bcrypt($user->id)),
            'name' => $user->name,

        ]);
    }
}