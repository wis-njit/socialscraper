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

    //Mock data to return to view in the absence of API response
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

    public $name = "";
    public function __construct(SMService $smsProvider, UserService $userService)
    {
        $this->middleware('auth');
        $this->smServiceProvider = $smsProvider;
        $this->userService = $userService;
    }

    /**
     *Handles the initial Facebook API request from the dashboard
     * and runs queries against all identified "interesting" endpoints
     * using the current user's OAuth token in parallel using Guzzle.
     * A valid token must be available for any call to be successful.
     *
     *All responses are returned to the view as an associative array
     * for dissemination.
     *
     * Passing a Facebook user's provider issued id and and associated
     * OAuth token will perform queries using it instead of the current
     * user.
     */
    public function index($fbUserId = null, $accessToken = null){
        $snsProfile = $this->userService->getUserProviderProfile(Auth::id(), SocialProvidersEnum::FACEBOOK);
        $accounts = $this->userService->getAllProviderAccounts(Auth::id());
        $client = new Client(['base_uri' => 'https://graph.facebook.com/']);

        $fbUserId = $snsProfile->provider_user_id;
        $accessToken = $snsProfile->access_token;

        $promises = [
            'userid' => $client->getAsync($fbUserId . '?access_token=' . $accessToken),
            'friends'   => $client->getAsync($fbUserId . '/friends' . '?access_token=' . $accessToken),
            'groups'   => $client->getAsync($fbUserId . '/groups' . '?access_token=' . $accessToken),
            'posts'   => $client->getAsync($fbUserId . '/posts' . '?access_token=' . $accessToken),
            //that's all for now folks
        ];

        $results = Promise\settle($promises)->wait();

        $responses = array(
            'userid_friends' => (string)$results['friends']['value']->getBody(),
            'postid' => self::mockDataHead . 'postid'. self::mockData,
            'posts' => (string)$results['posts']['value']->getBody(),
            'commentid' => self::mockDataHead . 'commentid'. self::mockData,
            'groups' => (string)$results['groups']['value']->getBody(),
            'groupid' => self::mockDataHead . 'groups'. self::mockData,
            'groupid_members' => self::mockDataHead . 'groupid_members'. self::mockData,
            'objectid_likes' => self::mockDataHead . 'objectid_likes'. self::mockData,
            'groupid_feed' => self::mockDataHead . 'groupid_feed'. self::mockData,
            'workexperienceid' => self::mockDataHead . 'workexperienceid'. self::mockData,
            'educationexperienceid' => self::mockDataHead . 'educationexperienceid'. self::mockData,
            'userid' => (string)$results['userid']['value']->getBody(),
            'offerid' => self::mockDataHead . 'offerid'. self::mockData
        );
        return view('facebook', compact('responses', 'accounts', 'name'));
    }

    /**
     * POST to a facebook test account to update the information
     *
     *
     */
    public function updateUserInfo(Request $request){
        $test_user_id = $request->get('test_user_id');
        echo $test_user_id;

        $name = $request->get('name');
        echo $name;

        $password = $request->get('password');
        echo $password;
    }

    /**
     * POST to a facebook test account to update the information
     *
     *
     */
    public function friendRequest(Request $request){

    }

}
