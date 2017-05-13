<?php

namespace app\Services;

use App\Provider;
use App\ProviderUserProfile;
use app\User;
use Auth;
use config\constants\SocialProvidersEnum;
use Laravel\Socialite\AbstractUser;

/**The social media service handles functions related to the discovery, linking, and creation
 * of users with respect to social network sites.
 *
 * The functions in this class walk a fine line of those that may be categorized as best being
 * listed within UserService.
 *
 * Class SMService
 * @package app\Services
 */
class SMService
{

    public function __construct()
    {
    }


    /**Find a user account based on the Socialite OAuth provider user's
     * account information. If one isn't found, we'll create a User account
     * as well as an associated ProviderUserProfile for the given provider.
     *
     * @param AbstractUser $user
     * @param string $oauthProvider
     * @return User|mixed|null
     */
    public function findOrCreateUser(AbstractUser $user, string $oauthProvider)
    {

        $authUser = $this->findUserByProviderProfile($user, $oauthProvider);

        if ($authUser){
            return $authUser;
        }else {
            return $this->createUser($user, $oauthProvider);
        }

    }


    /**Create a user account and provider profile using the passed Socialite user object data
     *
     * @param AbstractUser $user
     * @param string|null $oauthProvider
     * @return User
     */
    private function createUser(AbstractUser $user, string $oauthProvider = null)
    {
        $pup = new ProviderUserProfile();

        if($oauthProvider) {

            $pup = $this->createProviderProfile($user, $oauthProvider);
        }

        $nUser = $this->findUserByLocalProfile($user);
        //If there's no user found, create one
        if(!$nUser){
            $nUser = new User();
            \DB::transaction(function () use ($user, $pup, $nUser) {

                $nUser->email = $user->email ? : $user->id; //TODO test adequacy
                $nUser->password = bcrypt(bcrypt($user->id)); //FIXME use UUID
                $nUser->name = $user->name;
                $nUser->save();
                $nUser->snsProfile()->save($pup);

            });
        }
        //Otherwise we'll save/update the provider data which they've logged in with
        else{
            $nUser->snsProfile()->save($pup);
        }


        return $nUser;

    }


    /**Find and return the User account based on the passed provider type. If there is none,
     * we return null.
     *
     * @param AbstractUser $user
     * @param string $oauthProvider
     * @return User|null
     */
    private function findUserByProviderProfile(AbstractUser $user, string $oauthProvider)
    {
        //We will not lookup users' provider data by email as we
        //cannot ensure that the provider is ensuring they own that address

        $retUser = $this->getUserProviderProfile($user, $oauthProvider);

        if($retUser)
            return $retUser->user;
        else
            return null;
    }

    /**Find and return the user's ProviderUserProfile based on the provider's user id
     *
     * @param AbstractUser$user
     * @param string $oauthProvider
     * @return ProviderUserProfile
     */
    private function getUserProviderProfile(AbstractUser $user, string $oauthProvider){
        return ProviderUserProfile::whereHas('providerName' , function($query) use ($oauthProvider){
            $query->where('name', $oauthProvider);
        })
            ->where('provider_user_id', $user->id)
            ->first();
    }

    /**Find and return the User based on the Socialite OAuth account's email address
     *
     * @param AbstractUser $user
     * @return User
     */
    private function findUserByLocalProfile(AbstractUser $user)
    {
        return User::where('email', $user->email)->first();
    }


    private function handleGenericProviderData(AbstractUser $user, string $oauthProvider){

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

    /**
     * Given a returned SNS provider's user account and the provider type
     * link the current user to the existing provider profile by updating the
     * associated local user's id
     * @param AbstractUser $user
     * @param string $oauthProvider
     * @return User
     */
    public function linkProviderProfile(AbstractUser $user, string $oauthProvider){

        $provUser = $this->getUserProviderProfile($user, $oauthProvider);
        if(!$provUser)
            $provUser = $this->createProviderProfile($user, $oauthProvider);
        $provUser->user_id = Auth::id();
        return $provUser->save();
    }

    /**
     * Returns an SNS provider profile that includes email and name
     * based on the passed SNS provider user data and provider type
     * @param AbstractUser $user
     * @param string $oauthProvider
     * @return ProviderUserProfile
     */
    private function createProviderProfile(AbstractUser $user, string $oauthProvider)
    {

        $pup = new ProviderUserProfile();
        $pup->provider_type_id = Provider::getIdFromName($oauthProvider);
        $pup->email = $user->email;
        $pup->name = $user->name;
        $pup->provider_user_id = $user->id;
        $pup->access_token = $user->token;
        $pup->access_token_key = isset($user->tokenSecret) ? $user->tokenSecret : '';
        return $pup;
    }
}