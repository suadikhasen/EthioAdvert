<?php


namespace App\TelgramBot\Common\Services\Advertiser;


use App\TelgramBot\Object\Chat;
use Telegram\Bot\Objects\Message;

/**
 * Class PhotoService
 * @package App\TelgramBot\Common\Services\Advertiser
 */
class PhotoService
{
    /**
     * @param $message
     * @return bool
     */
    public static function isImage(Message $message):bool
    {
        if ($message->has('photo')){
            return true;
        }
        return false;
    }

    /**
     * @return mixed
     */
    public static function getPhotoID()
    {
        return Chat::getPhoto()[0]['file_id'];
    }
}
