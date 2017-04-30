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


    public function index($twitUserId = null, $accessToken = null, $accessTokenSecret = null){


        $snsProfile = $this->userService->getUserProviderProfile(Auth::id(), SocialProvidersEnum::TWITTER);
        $twitUserId = $snsProfile->provider_user_id;
        $accessToken = $snsProfile->access_token;
        $accessTokenSecret = $snsProfile->access_token_key;

        $stack = HandlerStack::create();

        $middleware = new Oauth1([
            'consumer_key'    => env('TWITTER_APP_ID'),
            'consumer_secret' => env('TWITTER_APP_SECRET'),
            'token'           => $accessToken,
            'token_secret'    => $accessTokenSecret
        ]);
        $stack->push($middleware);

        $client = new Client([
            'base_uri' => 'https://api.twitter.com/1.1/',
            'handler' => $stack,
            'auth' => 'oauth'
        ]);

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
            'lists_statuses' => $client->getAsync('lists/show.json' . '?owner_id=' . $twitUserId . '&list_id=' . '0')
        ];

        $results = Promise\settle($promises)->wait();

        $responses = array(
            //'users_show' => self::mockDataHead . 'users_show'. self::mockData,
            //'users_lookup' => self::mockDataHead . 'users_lookup'. self::mockData,
            //'search_tweets' => self::mockDataHead . 'search_tweets'. self::mockData,
            //'followers_ids' => self::mockDataHead . 'followers_ids'. self::mockData,
            //'geo_search' => self::mockDataHead . 'geo_search'. self::mockData,
            'users_userid' => self::mockDataHead . 'users_userid'. self::mockData,
            //'friendships_lookup' => self::mockDataHead . 'friendships_lookup'. self::mockData,
            //'friendships_show' => self::mockDataHead . 'friendships_show'. self::mockData,
            //'geo_id_placeid' => self::mockDataHead . 'geo_id_placeid'. self::mockData,
            //'lists_memberships' => self::mockDataHead . 'lists_memberships'. self::mockData,
            //'lists_show' => self::mockDataHead . 'lists_show'. self::mockData,
            'lists_statuses' => self::mockDataHead . 'lists_statuses'. self::mockData,
            'trends_available' => self::mockDataHead . 'trends_available'. self::mockData,
            'trends_closest' => self::mockDataHead . 'trends_closest'. self::mockData,
            'users_search' => self::mockDataHead . 'users_search'. self::mockData,
            'users_suggestions' => self::mockDataHead . 'users_suggestions'. self::mockData,
            'users_suggestions_slug_members' => self::mockDataHead . 'users_suggestions_slug_members'. self::mockData,
            'statuses_usertimeline' => self::mockDataHead . 'statuses_usertimeline'. self::mockData
        );

        foreach ($results as $key => $value){
            if($value['state'] === 'fulfilled' && $value['value'] !== null){
                $responses[$key] = (string)$value['value']->getBody();
            }
            else{
                $responses[$key] = (string)$value['reason']->getMessage();
            }
        }

        return view('twitter', compact('responses'));
    }

}
