<?php

namespace App\Http\Controllers\Auth;

use Auth;
use config\constants\SocialProvidersEnum;
use Illuminate\Support\Facades\Redirect;
use Socialite;
use App\Services\SMService;
use App\Http\Controllers\Controller;
use Request;

class AuthController extends Controller
{

    protected $smServiceProvider;
    private const NOLOGIN = 'nologin';


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(SMService $smsProvider)
    {
        $this->smServiceProvider = $smsProvider;
    }
    /**
     * Redirect the user to the social network provider authentication page.
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
     * @return Response
     */
    public function handleProviderCallback($oauthProvider)
    {

        try {
            //Logging in
            if($this->isLogin()){

                $user = Socialite::driver($oauthProvider)->user();

                $authUser = $this->smServiceProvider->findOrCreateUser($user, $oauthProvider);
                Auth::login($authUser, true);

                if($oauthProvider == SocialProvidersEnum::INSTAGRAM){
                    //flash & redirect to email entry
                }
                return Redirect::to('home');
            }
            //Linking accounts - verify
            else{

                //$user = Socialite::driver($oauthProvider)->stateless()->user(); //Twitter provider doesn't support stateless()
                $user = Socialite::driver($oauthProvider)->user();

                if(true)
                    Request::flash("Profile linked");
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
