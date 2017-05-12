<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Auth;
use config\constants\SocialProvidersEnum;
use Request;

class ProfileController extends Controller
{

    private $userService;

    public function __construct(UserService $userService){
        $this->middleware('auth');
        $this->userService = $userService;
    }
    public function profile()
    {
        $accounts = $this->userService->getAllProviderAccounts(Auth::id());
        $currentProvider = $this->userService->getCurrentSNSProvider();

        return view('profile', compact('accounts', 'currentProvider'));
    }

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
