<?php
namespace App\Http\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Promise;
use GuzzleHttp\Exception\ClientException;
use App\Services\SMService;
use App\Services\UserService;
use Auth;
use Config\Constants\SocialProvidersEnum;
use Illuminate\Http\Request;

class InstagramController extends ApiController
{

    //The API's base URI on which all requests will be made
    const URI = 'https://api.instagram.com';

    public function __construct(SMService $smProvider, UserService $userService)
    {
        parent::__construct( $smProvider,  $userService, SocialProvidersEnum::INSTAGRAM);
    }

    /**
     *Handles the initial Instagram API request from the dashboard
     * and runs queries against all identified "interesting" endpoints
     * using the current user's OAuth token in parallel using Guzzle.
     * A valid token must be available for any call to be successful.
     *
     *All responses are returned to the view as an associative array
     * for dissemination.
     *
     * Passing a Instagram user's provider issued id and and associated
     * OAuth token will perform queries using it instead of the current
     * user.
     *
     * @param $instUserId The provider assigned id
     * @param $accessToken A valid OAuth token
     *
     * @return \Illuminate\Http\Response
     */
    public function index($instUserId = null, $accessToken = null){
        $instUserId = $this->getProviderUserId();
        $accessToken = $this->getProviderToken();
        $accounts = $this->userService->getAllProviderAccounts(Auth::id());
        $client = new Client(['base_uri' => self::URI]);

        //TODO refactor using URI template
        /**Use Guzzle to make all requests in parallel and write responses
         *to an array for reading
         *
         * Only endpoints which we've been able to successfully make
         * requests to are included so far. Limitations are the permissions
         * configured for test users and the data populated in their accounts.
         */
        $promises = [
            'users_self' => $client->getAsync('/v1/users/self/' . '?access_token=' . $accessToken),
            'users_userid'   => $client->getAsync('/v1/users/' . $instUserId . '?access_token=' . $accessToken),
            'users_self_follows'   => $client->getAsync('/v1/users/self/follows' . '?access_token=' . $accessToken),
            'users_self_followedby'   => $client->getAsync('/v1/users/self/followed-by' . '?access_token=' . $accessToken),
            'users_self_requestedby'   => $client->getAsync('/v1/users/self/requested-by' . '?access_token=' . $accessToken),
            'users_userid_relationship'   => $client->getAsync('/v1/users/' . $instUserId . '/relationship' . '?access_token=' . $accessToken),
            'users_self_media_liked'   => $client->getAsync('/v1/users/self/media/liked' . '?access_token=' . $accessToken),
            //'media_mediaid_likes'   => $client->getAsync('/v1/users/media/' . $mediaid . '/likes' . '?access_token=' . $accessToken),
            //'media_mediaid'   => $client->getAsync('/v1/users/media/' . $mediaid . '?access_token=' . $accessToken),
            //'locations_locationid'   => $client->getAsync('/v1/users/locations/' . $locationid . '?access_token=' . $accessToken),
            //'locations_locationid_media_recent'   => $client->getAsync('/v1/users/locations/' . $locationid . '/media/recent/' . '?access_token=' . $accessToken),
        ];

        $results = Promise\settle($promises)->wait();
        $responses = $this->mapResponsesToArray($results);

        /**Until we can successfully make a request for an endpoint,
         * we'll return a mocked JSON response to display in the view
         * as a placeholder
         */
        $responses['media_mediaid_likes'] = 'Need media ID';
        $responses['media_mediaid'] = 'Need media ID';
        $responses['locations_locationid'] = 'Need location ID';
        $responses['locations_locationid_media_recent'] = 'Need location ID';

        return view('instagram', compact('responses', 'accounts'));
    }


    /**Modify the relationship between the current user and the target user.
     * You need to include an action parameter to
     * specify the relationship action you want to perform.
     * Valid actions are: 'follow', 'unfollow' 'approve' or 'ignore'.
     *
     * If no user ID is passed, the current will be used
     *
     * Requires scope: relationships
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function modifyRelationship(Request $request){
        $client = new Client(['base_uri' => self::URI]);
        $id = $request->get('user_id') ? $request->get('user_id') :$this->getProviderUserId();
        $params = ['query' => [

            'action' => $request->get('action'),
            'access_token' => $this->getProviderToken()
        ]];
        $response = "";

        try {
            $response = $client->post('/v1/users/' . $id . '/relationship', $params);
        } catch (ClientException $e) {
            $response = $e->getResponse();
        } finally {
            $request.session()->flash('response', (string)$response->getBody());
            return redirect('/user/instagram');
        }
    }

    /**Get a list of users who have liked this media.
     * The public_content scope is required for media that does not belong to the owner of the access_token.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function like(Request $request){
        $client = new Client(['base_uri' => self::URI]);
        $params = ['query' => [

            'access_token' => $this->getProviderToken()
        ]];
        $mediaId = $request->get('media_id');

        $response = "";

        try {
            $response = $client->post('/v1/media/' . $mediaId . '/likes', $params);
        } catch (ClientException $e) {
            $response = $e->getResponse();
        } finally {
            dd((string)$response->getBody());
            $request.session()->flash('response', (string)$response->getBody());
            return redirect('/user/instagram');
        }
    }

}