<?php

namespace App\Http\Controllers;

class HomeController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        //
    }

    public function index() {
//        $this->pushToSheet([["Wheel", "$20.50", "4", "3/1/2016"]]);
//        array(array("Wheel", "$20.50", "4", "3/1/2016"))
        return view("home.index");
    }

    private function pushToSheet(array $data) {
        $channel = curl_init();
        curl_setopt($channel, CURLOPT_URL, "http://damxuanlap.com/api/sheet");
        curl_setopt($channel, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($channel, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($channel, CURLOPT_POSTFIELDS, json_encode(array("data"=>$data,"token"=>"damxuanlap1w6l")));
        curl_setopt($channel, CURLOPT_RETURNTRANSFER, 1);

        //execute post
        $response = curl_exec($channel);
        //close connection
        curl_close($channel);
        print_r($response);
        $responseInJson = json_decode($response);
        return $responseInJson;
    }

    //
}
