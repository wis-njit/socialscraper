<?php

namespace app\Services;

use App\Provider;
use App\ProviderUserProfile;
use config\constants\SocialProvidersEnum;
use app\User;

class SMService
{

    public function __construct()
    {
    }

    public function findOrCreateUser($user, $oauthProvider)
    {

        $authUser = $this->findUserAccount($user, $oauthProvider);

        if ($authUser){
            return $authUser;
        }else {
            return $this->createUser($user, $oauthProvider);
        }

    }


    private function createUser($user, $oauthProvider = null)
    {
        $pup = new ProviderUserProfile();

        if($oauthProvider) {

            $pup->provider_type_id = Provider::getIdFromName($oauthProvider);
            $pup->email = $user->email;
            $pup->name = $user->name;
            $pup->provider_user_id = $user->id;
        }

        $nUser = new User();
        \DB::transaction(function () use ($user, $pup, $nUser) {

            $nUser->email = $user->email ? : ' ';
            $nUser->password = bcrypt(bcrypt($user->id)); //FIXME use UUID
            $nUser->name = $user->name;
            $nUser->save();
            $nUser->snsProfile()->save($pup);

        });
        return $nUser;

    }


    private function findUserAccount($user, $oauthProvider)
    {
        //Look for a user with the email address
        $authUser = $this->findUserByLocalProfile($user);
        if(!$authUser) {
            $authUser = $this->findUserByProviderProfile($user, $oauthProvider);
        }
        return $authUser;
    }


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


    private function findUserByLocalProfile($user)
    {
        return User::where('email', $user->email)->first();
    }

    private function handleGenericProviderData($user, $oauthProvider){

        //TODO Refactor into COR handlers

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