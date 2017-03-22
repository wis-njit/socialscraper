<?php

namespace App\Http\Controllers;

use App\Provider;
use Illuminate\Http\Request;
use App\Services\UserService;

class ProfileController extends Controller
{

    private $userService;

    public function __construct(UserService $userService){
        $this->middleware('auth');
        $this->userService = $userService;
    }
    public function profile()
    {
        return view('profile');
    }

    public function getActiveProviders(){
        $accounts = $this->userService->getAllProviderAccounts();
        $providers = Provider::getAllProviderNames();

        return view('profile', compact('accounts', 'providers'));
    }
}
