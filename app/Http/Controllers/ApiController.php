<?php

namespace App\Http\Controllers;

use App\Services\SMService;
use App\Services\UserService;
use Auth;

class ApiController extends Controller
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
    protected $apiName;
    protected $snsProfile;

    public function __construct(SMService $smProvider, UserService $userService, string $apiName)
    {
        $this->middleware('auth');
        $this->smServiceProvider = $smProvider;
        $this->userService = $userService;
        $this->apiName = $apiName;
    }

    /**Returns an associative array of responses mapped to their passed
     * key given an array of fulfilled or rejected promises
     * @param array $results
     * @return array
     */
    function mapResponsesToArray(array $results){

        $responses = array();

        foreach ($results as $key => $value){
            if($value['state'] === 'fulfilled' && $value['value'] !== null){
                $responses[$key] = (string)$value['value']->getBody();
            }
            else{
                $responses[$key] = (string)$value['reason']->getMessage();
            }
        }
        return $responses;
    }

    /**
     *Retrieve and set the ProviderUserProfile object for subsequent calls
     */
    private function setUserProviderProfile(){
        $this->snsProfile = $this->userService->getUserProviderProfile(Auth::id(), $this->apiName);
    }

    /**Retrieve the stored provider user id
     *
     * @return mixed
     */
    protected function getProviderUserId(){
        $this->checkProfile();
        return $this->snsProfile->provider_user_id;
    }

    /**Retrieve the store provider access token
     *
     * @return mixed
     */
    protected function getProviderToken(){
        $this->checkProfile();
        return $this->snsProfile->access_token;
    }

    /**
     *Verify if the ProviderUserProfileObject is populated prior to read
     * and if not, populate it
     */
    private function checkProfile(){
        if(!$this->snsProfile) {
            $this->setUserProviderProfile();
        }
    }
}
