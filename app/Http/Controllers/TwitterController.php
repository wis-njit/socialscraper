<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Promise;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Subscriber\Oauth\Oauth1;
use GuzzleHttp\Exception\ClientException;
use App\Services\SMService;
use App\Services\UserService;
use Auth;
use Config\Constants\SocialProvidersEnum;

class TwitterController extends ApiController
{

    //The API's base URI on which all requests will be made
    const URI = 'https://api.twitter.com/1.1/';

    public function __construct(SMService $smProvider, UserService $userService)
    {
        parent::__construct( $smProvider,  $userService, SocialProvidersEnum::TWITTER);
    }

    /**Creates an OAuth1 client for twitter connections
     *
     * @return Client
     */
    private function createOauthClient(): Client
    {
        $stack = HandlerStack::create();

        /**Using Guzzle's OAuth1 subscriber, we compose the headers,
         * parameters, etc needed for the request.
         */
        $middleware = new Oauth1([
            'consumer_key' => env('TWITTER_APP_ID'),
            'consumer_secret' => env('TWITTER_APP_SECRET'),
            'token' => $this->getProviderToken(),
            'token_secret' => $this->getProviderTokenKey()
        ]);
        $stack->push($middleware);

        $client = new Client([
            'base_uri' => self::URI,
            'handler' => $stack,
            'auth' => 'oauth'
        ]);
        return $client;
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
     * @return \Illuminate\Http\Response
     */
    public function index($twitUserId = null, $accessToken = null, $accessTokenSecret = null){

        $twitUserId = $twitUserId ? : $this->getProviderUserId();
        $accounts = $this->userService->getAllProviderAccounts(Auth::id());
        $client = $this->createOauthClient();
        $params = ['query' => [
            'user_id' => $twitUserId
        ]];

        /**Use Guzzle to make all requests in parallel and write responses
         *to an array for reading
         *
         * Only endpoints which we've been able to successfully make
         * requests to are included so far.
         */
        $promises = [
            'users_show' => $client->getAsync('users/show.json', $params),
            'users_lookup' => $client->getAsync('users/lookup.json', $params),
            'search_tweets' => $client->getAsync('search/tweets.json' . '?q=' . urlencode('searched text')),
            'followers_ids' => $client->getAsync('followers/ids.json', $params),
            'geo_search' => $client->getAsync('geo/search.json' . '?ip=172.56.132.25'),
            'friendships_lookup' => $client->getAsync('friendships/lookup.json', $params),
            'friendships_show' => $client->getAsync('friendships/show.json' . '?target_id=' . $twitUserId),
            'geo_id_placeid' => $client->getAsync('geo/id/' . '7238f93a3e899af6' . '.json'),
            'lists_memberships' => $client->getAsync('lists/memberships.json', $params),
            'lists_show' => $client->getAsync('lists/show.json' . '?owner_id=' . $twitUserId . '&list_id=' . '0'),
            'trends_available' => $client->getAsync('trends/available.json'),
            'trends_closest' => $client->getAsync('trends/closest.json', ['query' => ['lat' => '37.781157', 'long' => '-122.400612831116']]),
            'users_search' => $client->getAsync('users/search.json?q=' . 'golden'),
            'users_suggestions' => $client->getAsync('users/suggestions.json'),
            'users_suggestions_slug_members' => $client->getAsync('users/suggestions/' . 'funny' . '/members.json'),
            'statuses_usertimeline' => $client->getAsync('statuses/user_timeline.json'),
            //'lists_statuses' => $client->getAsync('lists/statuses.json', ['query' => ['slug' => 'funny', 'owner_screen_name' => 'azizansari', 'count' => '1']])

        ];

        $results = Promise\settle($promises)->wait();
        $responses = $this->mapResponsesToArray($results);

        /**Until we can successfully make a request for an endpoint,
         * we'll return a mocked JSON response to display in the view
         * as a placeholder
         */
        $responses['users_userid'] = self::mockDataHead . 'users_userid'. self::mockData;
        $responses['lists_statuses'] = self::mockDataHead . 'lists_statuses'. self::mockData;

        return view('twitter', compact('responses', 'accounts'));
    }

    /**Updates the authenticating user’s current status, also known as Tweeting
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function updateStatus(Request $request){

        $params = [ 'query' => [
            'status' => $request->get('status'),
            'in_reply_to_status_id' => $request->get('in_reply_to_status_id'),
            'possibly_sensitive' => $request->get('possibly_sensitive'),
            'lat' => $request->get('lat'),
            'long' => $request->get('long'),
            'place_id' => $request->get('place_id'),
            'display_coordinates' => $request->get('display_coordinates'),
            'trim_user' => $request->get('trim_user'),
            'media_ids' => $request->get('media_ids')
        ]];
        $response = "";

        try {
            $response = $this->createOauthClient()->post('statuses/update.json', $params);
        } catch (ClientException $e) {
            $response = $e->getResponse();
        } finally {
            $request.session()->flash('response', (string)$response->getBody());
            return redirect('/user/twitter');
        }

    }


    /**Sets some values that users are able to set under the “Account” tab of their settings page.
     * Only the parameters specified will be updated.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function updateAccountStatus(Request $request){
        $params = [ 'query' => [
            'name' => $request->get('name'),
            'url' => $request->get('url'),
            'location' => $request->get('location'),
            'description' => $request->get('description'),
            'profile_link_color' => $request->get('profile_link_color'),
            'include_entities' => $request->get('include_entities'),
            'skip_status' => $request->get('skip_status')
        ]];
        $response = "";

        try {
            $response = $this->createOauthClient()->post('account/update_profile.json', $params);
        } catch (ClientException $e) {
            $response = $e->getResponse();
        } finally {
            $request.session()->flash('response', (string)$response->getBody());
            return redirect('/user/twitter');
        }
    }


}
