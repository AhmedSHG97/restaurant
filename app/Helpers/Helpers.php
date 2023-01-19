<?php

use App\Models\Settings;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Client;

if(!function_exists("getPassportClient")){
    function getPassportClient()
    {
        return Client::where("password_client", 1)->first();
    }
}
if (!function_exists("userSession")) {
    function userSession(){
        return Auth::user();
    }
}


?>