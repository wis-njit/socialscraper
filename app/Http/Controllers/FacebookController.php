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

    //The API's base URI on which all requests will be made
    const URI = 'https://graph.facebook.com/';

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
     *
     * @param $fbUserId The provider assigned id
     * @param $accessToken A valid OAuth token
     *
     * @return view
     */
    public function index($fbUserId = null, $accessToken = null){
        $snsProfile = $this->userService->getUserProviderProfile(Auth::id(), SocialProvidersEnum::FACEBOOK);
        $accounts = $this->userService->getAllProviderAccounts(Auth::id());
        $client = new Client(['base_uri' => self::URI]);

        $fbUserId = $snsProfile->provider_user_id;
        $accessToken = $snsProfile->access_token;

        //TODO refactor using URI template
        /**Use Guzzle to make all requests in parallel and write responses
         *to an array for reading
         *
         * Only endpoints which we've been able to successfully make
         * requests to are included so far. Limitations are the permissions
         * configured for test users and the data populated in their accounts.
         */
        $promises = [
            'userid' => $client->getAsync($fbUserId . '?access_token=' . $accessToken),
            'userid_friends'   => $client->getAsync($fbUserId . '/friends' . '?access_token=' . $accessToken),
            'groups'   => $client->getAsync($fbUserId . '/groups' . '?access_token=' . $accessToken),
            'posts'   => $client->getAsync($fbUserId . '/posts' . '?access_token=' . $accessToken),
        ];

        $results = Promise\settle($promises)->wait();

        /**Until we can successfully make a request for an endpoint,
         * we'll return a mocked JSON response to display in the view
         * as a placeholder
         */
        $responses = $this->mapResponsesToArray($results);
        $responses['postid'] = self::mockDataHead . 'postid'. self::mockData;
        $responses['commentid'] = self::mockDataHead . 'commentid'. self::mockData;
        $responses['groupid'] = self::mockDataHead . 'groupid'. self::mockData;
        $responses['groupid_members'] = self::mockDataHead . 'groupid_members'. self::mockData;
        $responses['objectid_likes'] = self::mockDataHead . 'objectid_likes'. self::mockData;
        $responses['groupid_feed'] = self::mockDataHead . 'groupid_feed'. self::mockData;
        $responses['workexperienceid'] = self::mockDataHead . 'workexperienceid'. self::mockData;
        $responses['educationexperienceid'] = self::mockDataHead . 'educationexperienceid'. self::mockData;
        $responses['offerid'] = self::mockDataHead . 'offerid'. self::mockData;

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
