<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Auth;
use config\constants\SocialProvidersEnum;
use Request;

/**
 * Fields all requests associated with a User's account
 *
 * Class ProfileController
 * @package App\Http\Controllers
 */
class ProfileController extends Controller
{

    private $userService;

    public function __construct(UserService $userService){
        $this->middleware('auth');
        $this->userService = $userService;
    }

    /**The default function, retrieves all of a user's linked accounts
     * ,the current SNS provider they've logged in using and sends them
     * to the profile view.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function profile()
    {
        $accounts = $this->userService->getAllProviderAccounts(Auth::id());
        $currentProvider = $this->userService->getCurrentSNSProvider();

        return view('profile', compact('accounts', 'currentProvider'));
    }

    /**Disassociates(unlinks) a user's current account from the passed
     * provider's profile.
     *
     * @param $oauthProvider
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function disassociateProvider($oauthProvider){

        if($provEnum = SocialProvidersEnum::getValue($oauthProvider)){

            if($deletedModel = $this->userService->disassociateProviderAccount($provEnum, Auth::id())){
                Request::session()->flash('alert-success', 'Account unlinked');
            }
            else{
                Request::session()->flash('alert-warning', 'Unable to unlink account');
            }
        }
        $accounts = $this->userService->getAllProviderAccounts(Auth::id());
        $currentProvider = $this->userService->getCurrentSNSProvider();

        return view('profile', compact('accounts', 'currentProvider'));
    }
}
