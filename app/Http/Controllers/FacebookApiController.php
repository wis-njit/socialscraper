<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FacebookApiController extends Controller
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

    public function runQueries(){

        $responses = array(
            'userid_friendlists' => self::mockDataHead . 'userid_friendlists'. self::mockData,
            'postid' => self::mockDataHead . 'postid'. self::mockData,
            'commentid' => self::mockDataHead . 'commentid'. self::mockData,
            'groupid' => self::mockDataHead . 'groupid'. self::mockData,
            'groupid_members' => self::mockDataHead . 'groupid_members'. self::mockData,
            'objectid_likes' => self::mockDataHead . 'objectid_likes'. self::mockData,
            'groupid_feed' => self::mockDataHead . 'groupid_feed'. self::mockData,
            'workexperienceid' => self::mockDataHead . 'workexperienceid'. self::mockData,
            'educationexperienceid' => self::mockDataHead . 'educationexperienceid'. self::mockData,
            'userid' => self::mockDataHead . 'userid'. self::mockData,
            'offerid' => self::mockDataHead . 'offerid'. self::mockData
        );

        //$responses = "";
        return view('facebookapi', ['responses' => $responses]);
    }
}
