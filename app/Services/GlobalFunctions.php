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

if (!function_exists('channel_approve_status')){
    function channel_approve_status($status)
    {
      if($status){
          return 'Approved';
      }
      return 'Not Approved';
    }
}

if (!function_exists('channelLevel')){
    function channelLevel($channel)
    {
      if($channel->level_id === null){
          return '<b>not Assigned</b>';       
      }
     return $channel->level->level_name;
    }
}