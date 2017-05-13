<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserService;
use Auth;

class HomeController extends Controller
{

    private $userService;

    public function __construct(UserService $userService){
        $this->middleware('auth');
        $this->userService = $userService;
    }

    /**
     * Show the application dashboard.
     *
     * Retrieve all the provider accounts a user has linked to their account
     * and deliver them to the home page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $accounts = $this->userService->getAllProviderAccounts(Auth::id());
        return view('home', compact('accounts'));
    }


}
