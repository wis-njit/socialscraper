<?php
namespace App\Http\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Promise;
use App\Services\SMService;
use App\Services\UserService;
use Auth;
use Config\Constants\SocialProvidersEnum;

class InstagramController extends Controller
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
     * Show the Instagram data.
     */
    public function index($instUserId = null, $accessToken = null){
        $snsProfile = $this->userService->getUserProviderProfile(Auth::id(), SocialProvidersEnum::INSTAGRAM);
        $accounts = $this->userService->getAllProviderAccounts(Auth::id());
        $client = new Client(['base_uri' => 'https://api.instagram.com']);

        $instUserId = $snsProfile->provider_user_id;
        $accessToken = $snsProfile->access_token;

        //TODO refactor using URI template
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
        $responses = array();

        foreach ($results as $key => $value){
            if($value['state'] === 'fulfilled' && $value['value'] !== null){
                $responses[$key] = (string)$value['value']->getBody();
            }
            else{
                $responses[$key] = (string)$value['reason']->getMessage();
            }
        }

        //Add unimplemented responses for the time being - for display
        $responses['media_mediaid_likes'] = 'Need media ID';
        $responses['media_mediaid'] = 'Need media ID';
        $responses['locations_locationid'] = 'Need location ID';
        $responses['locations_locationid_media_recent'] = 'Need location ID';

        return view('instagram', compact('responses', 'accounts'));
    }


}