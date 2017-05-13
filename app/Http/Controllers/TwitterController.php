<?php


namespace App\Http\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Promise;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Subscriber\Oauth\Oauth1;
use App\Services\SMService;
use App\Services\UserService;
use Auth;
use Config\Constants\SocialProvidersEnum;

class TwitterController extends Controller
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
    const URI = 'https://api.twitter.com/1.1/';

    protected $smServiceProvider;
    protected $userService;

    public function __construct(SMService $smsProvider, UserService $userService)
    {
        $this->middleware('auth');
        $this->smServiceProvider = $smsProvider;
        $this->userService = $userService;
    }


    /**
     *Handles the initial Twitter API request from the dashboard
     * and runs queries against all identified "interesting" endpoints
     * using the current user's OAuth token in parallel using Guzzle.
     * A valid token must be available for any call to be successful.
     *
     *All responses are returned to the view as an associative array
     * for dissemination.
     *
     * Passing a Twitter user's provider issued id and and associated
     * OAuth token will perform queries using it instead of the current
     * user.
     *
     * @param $twitUserId The provider assigned id
     * @param $accessToken A valid OAuth token
     * @param $accessTokenSecret The OAuth1 token's secret
     *
     * @return view
     */
    public function index($twitUserId = null, $accessToken = null, $accessTokenSecret = null){

        $snsProfile = $this->userService->getUserProviderProfile(Auth::id(), SocialProvidersEnum::TWITTER);
        $twitUserId = $snsProfile->provider_user_id;
        $accessToken = $snsProfile->access_token;
        $accessTokenSecret = $snsProfile->access_token_key;
        $accounts = $this->userService->getAllProviderAccounts(Auth::id());
        $stack = HandlerStack::create();

        /**Using Guzzle's OAuth1 subscriber, we compose the headers,
         * parameters, etc needed for the request.
        */
        $middleware = new Oauth1([
            'consumer_key'    => env('TWITTER_APP_ID'),
            'consumer_secret' => env('TWITTER_APP_SECRET'),
            'token'           => $accessToken,
            'token_secret'    => $accessTokenSecret
        ]);
        $stack->push($middleware);

        $client = new Client([
            'base_uri' => self::URI,
            'handler' => $stack,
            'auth' => 'oauth'
        ]);

        //TODO refactor using URI template
        /**Use Guzzle to make all requests in parallel and write responses
         *to an array for reading
         *
         * Only endpoints which we've been able to successfully make
         * requests to are included so far.
         */
        $promises = [
            'users_show' => $client->getAsync('users/show.json' . '?user_id=' . $twitUserId),
            'users_lookup' => $client->getAsync('users/lookup.json' . '?user_id=' . $twitUserId),
            'search_tweets' => $client->getAsync('search/tweets.json' . '?q=' . urlencode('searched text')),
            'followers_ids' => $client->getAsync('followers/ids.json' . '?user_id=' . $twitUserId),
            'geo_search' => $client->getAsync('geo/search.json' . '?ip=172.56.132.25'),
            'friendships_lookup' => $client->getAsync('friendships/lookup.json' . '?user_id=' . $twitUserId),
            'friendships_show' => $client->getAsync('friendships/show.json' . '?target_id=' . $twitUserId),
            'geo_id_placeid' => $client->getAsync('geo/id/' . '7238f93a3e899af6' . '.json'),
            'lists_memberships' => $client->getAsync('lists/memberships.json' . '?user_id=' . $twitUserId),
            'lists_show' => $client->getAsync('lists/show.json' . '?owner_id=' . $twitUserId . '&list_id=' . '0'),

        ];

        $results = Promise\settle($promises)->wait();
        $responses = $this->mapResponsesToArray($results);

        /**Until we can successfully make a request for an endpoint,
         * we'll return a mocked JSON response to display in the view
         * as a placeholder
         */
        $responses['users_userid'] = self::mockDataHead . 'users_userid'. self::mockData;
        $responses['lists_statuses'] = self::mockDataHead . 'lists_statuses'. self::mockData;
        $responses['trends_available'] = self::mockDataHead . 'trends_available'. self::mockData;
        $responses['trends_closest'] = self::mockDataHead . 'trends_closest'. self::mockData;
        $responses['users_search'] = self::mockDataHead . 'users_search'. self::mockData;
        $responses['users_suggestions'] = self::mockDataHead . 'users_suggestions'. self::mockData;
        $responses['users_suggestions_slug_members'] = self::mockDataHead . 'users_suggestions_slug_members'. self::mockData;
        $responses['statuses_usertimeline'] = self::mockDataHead . 'statuses_usertimeline'. self::mockData;

        return view('twitter', compact('responses', 'accounts'));
    }

    public function updateStatus(Request $request){

    }

    public function updateAccountStatus(Request $request){

    }
}
