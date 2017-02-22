<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\SMService;
use Auth;
use config\constants\SocialProvidersEnum;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Redirect;
use Socialite;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';
    protected $smServiceProvider;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(SMService $smsProvider)
    {
        $this->middleware('guest', ['except' => 'logout']);
        $this->smServiceProvider = $smsProvider;
    }


    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return Response
     */
    public function redirectToProvider($oauthProvider)
    {
        return Socialite::driver($oauthProvider)->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return Response
     */
    public function handleProviderCallback($oauthProvider)
    {

        try {
            $user = Socialite::driver($oauthProvider)->user();
        } catch (Exception $e) {
            return Redirect::to('auth/' + $oauthProvider);
        }
        $authUser = $this->smServiceProvider->findOrCreateUser($user, $oauthProvider);
        Auth::login($authUser, true);

        if($oauthProvider == SocialProvidersEnum::INSTAGRAM){
            //flash & redirect to email entry
        }
        return Redirect::to('home');
        //TODO comment done testing
        //return serialize($user);
    }
}

