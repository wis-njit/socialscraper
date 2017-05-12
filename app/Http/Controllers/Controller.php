<?php

namespace App\Http\Controllers;

use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

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
}
