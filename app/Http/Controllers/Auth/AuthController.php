<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\SMService;
use App\Services\UserService;
use Auth;
use config\constants\SocialProvidersEnum;
use Illuminate\Support\Facades\Redirect;
use Request;
use Socialite;

/**
 * Class AuthController
 * @package App\Http\Controllers\Auth
 *
 * This controller facilitates the ability for users to authenticate through their respective SNS providers.
 * The aforementioned routes/web.php functions redirectToProvider and handleProviderCallback serve as the main
 * conduit between our application and Twitter, Instagram, and Facebook.
 */
class AuthController extends Controller
{

    protected $smServiceProvider;
    protected $userService;
    const NOLOGIN = 'nologin';


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(SMService $smsProvider, UserService $userService)
    {
        $this->smServiceProvider = $smsProvider;
        $this->userService = $userService;
    }

    /**
     * Redirect the user to the social network provider authentication page.
     *
     * The redirectToProvider function uses Socialite to request data from the SNS provider,
     * while simultaneously setting a session value to indicate if the request is to authenticate
     * and log a user in, or simply authenticate a user, enabling them to link their current account
     * to another SNS account.
     *
     * @return Response
     */
    public function redirectToProvider($oauthProvider)
    {
        return Socialite::driver($this->setNoLoginSessionData($oauthProvider))->redirect();
    }

    /**
     * Obtain the user information from the social network provider.
     *
     * On redirect back to our application from the SNS provider, the handleProviderCallback
     * is fired and either logs a user in, or simply performs the process to link their accounts.
     *
     * If the user is logging in, the user data returned is checked against known accounts,
     * and if one doesn’t exist, creates it. It also records the OAuth token for future calls to
     * the respective SNS provider, on their behalf, and sets a session value indicating the
     * current SNS provider.
     *
     * If the user is linking an account, the user data returned is inserted into the database,
     * and associated with the current user’s ID. It also records the OAuth token for future calls.
     *
     * @return Response
     */
    public function handleProviderCallback($oauthProvider)
    {

        try {
            //Logging in
            if($this->isLogin()){

                $user = Socialite::driver($oauthProvider)->user();

                $authUser = $this->smServiceProvider->findOrCreateUser($user, $oauthProvider);
                $this->userService->updateUserSNSToken($user, $authUser, $oauthProvider);

                Auth::login($authUser, true);
                $this->userService->setCurrentSNSProvider($oauthProvider);

                if($oauthProvider == SocialProvidersEnum::INSTAGRAM){
                    //flash & redirect to email entry
                }
                return Redirect::to('home');
            }
            //Linking accounts - we are not logging the returned user in, just pulling their info
            else{

                //$user = Socialite::driver($oauthProvider)->stateless()->user(); //Twitter provider doesn't support stateless()
                $user = Socialite::driver($oauthProvider)->user();

                $linkedUser = $this->smServiceProvider->linkProviderProfile($user, $oauthProvider);
                if($linkedUser){
                    $this->userService->updateUserSNSToken($user, Auth::user(), $oauthProvider);
                    Request::session()->flash('alert-success', 'Profile linked');
                }
                else {
                    Request::session()->flash('alert-warning', 'Profile link failed');
                }

                return Redirect::to('user/profile');
            }
        } catch (Exception $e) {
            return Redirect::to('auth/' + $oauthProvider);
        }
    }

    /**
     * Check session to see if this oauth call was for login or just checking if user owns another SNS account(linking)
     * @return boolean
     */
    private function isLogin()
    {
        $isNoAuth = Request::session()->get(self::NOLOGIN, false);
        Request::session()->forget(self::NOLOGIN);
        return !$isNoAuth;
    }

    /**
     * Check if we're simply checking if a user owns an account, and if so, set session data to indicate
     * it so we can handle things appropriately in subsequent calls
     * @param $oauthProvider
     * @return string
     */
    private function setNoLoginSessionData($oauthProvider)
    {
        if (strpos($oauthProvider, self::NOLOGIN) !== false) {
            Request::session()->put(self::NOLOGIN, true);
            $oauthProvider = str_replace(self::NOLOGIN, '', $oauthProvider);
        }
        return $oauthProvider;
    }
}
