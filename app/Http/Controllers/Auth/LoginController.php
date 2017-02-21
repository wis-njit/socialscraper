<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use DB;
use Socialite;
use App\User;
use Auth;
use Illuminate\Support\Facades\Redirect;

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

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
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

        $authUser = $this->findOrCreateUser($user, $oauthProvider);

        Auth::login($authUser, true);

        if($oauthProvider == 'instagram'){
            //flash & redirect to email entry
        }
        return Redirect::to('home');
        //TODO comment done testing
        //return serialize($user);
    }

    /**
     * Return user if exists; create and return if doesn't
     *
     * @param $user
     * @return User
     */
    private function findOrCreateUser($user, $oauthProvider)
    {
        //TODO Refactor for multiple providers
        //if ($authUser = DB::table('users')->where(['oauthprovider' => $oauthProvider, '' => $user->id])->first()) {

        //Static Google/FB/Twitter
        if($oauthProvider != 'instagram'){
            $authUser = User::where('email', $user->email)->first();
            if ($authUser){
                return $authUser;
            }else {
                return User::create([
                    'email' => $user->email,
                    'password' => bcrypt(bcrypt($user->id)),
                    'name' => $user->name,

                ]);

            }
        }
        //Instagram for now
        else{
            $instEmail = $user->id . '@instagram.com';
            $authUser = User::where('email', $instEmail)->first();
            if ($authUser){
                return $authUser;
            }else {
                return User::create([
                    'email' => $instEmail,
                    'password' => bcrypt(bcrypt($user->id)),
                    'name' => $user->name,

                ]);

            }
        }
    }
}

