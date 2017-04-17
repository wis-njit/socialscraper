<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Promise;
use App\Services\SMService;
use App\Services\UserService;
use Auth;
use Config\Constants\SocialProvidersEnum;

class FacebookController extends Controller
{

    const mockDataHead = '{"';
    const mockData = ' ":[
                          {
                             "id":1,
                             "first_name":"Raymond",
                             "last_name":"Ramos",
                             "email":"rramos0@alexa.com",
                             "gender":"Male",
                             "ip_address":"165.64.230.96"
                          },
                          {
                             "id":2,
                             "first_name":"Mildred",
                             "last_name":"Wood",
                             "email":"mwood1@joomla.org",
                             "gender":"Female",
                             "ip_address":"110.252.219.18"
                          },
                          {
                             "id":3,
                             "first_name":"Frances",
                             "last_name":"Greene",
                             "email":"fgreene2@free.fr",
                             "gender":"Female",
                             "ip_address":"154.145.59.188"
                          }
                       ]
                    }';

    protected $smServiceProvider;
    protected $userService;

    public function __construct(SMService $smsProvider, UserService $userService)
    {
        $this->middleware('auth');
        $this->smServiceProvider = $smsProvider;
        $this->userService = $userService;
    }

    /**
     * Show the application dashboard.
     *
     *
     */
    public function index($fbUserId = null, $accessToken = null){
        $snsProfile = $this->userService->getUserProviderProfile(Auth::id(), SocialProvidersEnum::FACEBOOK);
        $client = new Client(['base_uri' => 'https://graph.facebook.com/']);

        $fbUserId = $snsProfile->provider_user_id;
        $accessToken = $snsProfile->access_token;

        $promises = [
            'userid' => $client->getAsync($fbUserId . '?access_token=' . $accessToken),
            'friends'   => $client->getAsync($fbUserId . '/friends' . '?access_token=' . $accessToken),
            //that's all for now folks
        ];

        $results = Promise\settle($promises)->wait();

        $responses = array(
            'userid_friends' => (string)$results['friends']['value']->getBody(),
            'postid' => self::mockDataHead . 'postid'. self::mockData,
            'commentid' => self::mockDataHead . 'commentid'. self::mockData,
            'groupid' => self::mockDataHead . 'groupid'. self::mockData,
            'groupid_members' => self::mockDataHead . 'groupid_members'. self::mockData,
            'objectid_likes' => self::mockDataHead . 'objectid_likes'. self::mockData,
            'groupid_feed' => self::mockDataHead . 'groupid_feed'. self::mockData,
            'workexperienceid' => self::mockDataHead . 'workexperienceid'. self::mockData,
            'educationexperienceid' => self::mockDataHead . 'educationexperienceid'. self::mockData,
            'userid' => (string)$results['userid']['value']->getBody(),
            'offerid' => self::mockDataHead . 'offerid'. self::mockData
        );
        return view('facebook', compact('responses'));
    }

}
