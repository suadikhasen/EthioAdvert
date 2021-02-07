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

if (!function_exists('approve_status')){
    function approve_status($status)
    {
      if($status){
          return 'Approved';
      }
      return 'Not Approved';
    }
}

if (!function_exists('payment_status')){
    function payment_status($status)
    {
      if($status){
          return 'Paid';
      }
      return 'Un Paid';
    }
}

if (!function_exists('active_status')){
    function active_status($status)
    {
      if($status === 1){
          return 'Pending';
      }elseif($status === 2){
          return 'Not Started';
      }elseif($status === 3){
          return 'Active Now ';
      }elseif($status === 4){
          return 'finished';
      }elseif($status === 5){
          return 'Expired';
      }
      
    }
}

if (!function_exists('channel_removed_status')){
    function channel_removed_status($status)
    {
      if($status){
          return 'removed';
      }
      return 'Not removed';
    }
}


if (!function_exists('channelLevel')){
    function channelLevel($channel)
    {
      if($channel->level_id === null){
          return 'not Assigned';       
      }
     return $channel->level->level_name;
    }
}