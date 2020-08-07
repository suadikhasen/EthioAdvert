<?php


namespace App\TelgramBot\Common\Services\Messages;


use App\TelgramBot\Object\Chat;

class ChannelMessages
{
   public static function noChannel()
   {
       Chat::sendTextMessage('there is no channel registered on your account');
   }

    public static function notAuthorize()
    {
        Chat::sendTextMessage('you are not authorized to access this action');
    }

    public static function perDayPost()
    {
        Chat::sendTextMessage('Please Send The number of post you need to change');
    }

    public static function updatePerDayPost()
    {
        Chat::sendTextMessage('Per Day Post Updated Successfully');
    }

    public static function invalidValue()
    {
        Chat::sendTextMessage('Please Send valid inout');
    }
}
