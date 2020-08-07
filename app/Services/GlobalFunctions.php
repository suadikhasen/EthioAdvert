<?php
use App\User;
use App\TelgramBot\Object\Chat;
use App\UserPaymentMethod;

if(!function_exists('isRegistered'))
{
    function isRegistered($chat_id){
      return User::where('chat_id',$chat_id)->exists();
    }
}

if (!function_exists('userType')){
    function userType(){
        $user = User::where('chat_id',Chat::$chat_id)->first();
        return $user->type;
    }
}

if (!function_exists('is_user_have_Payment_method')){
    function is_user_have_Payment_method()
    {
        return UserPaymentMethod::where('chat_id',Chat::$chat_id)->exists();
    }
}

