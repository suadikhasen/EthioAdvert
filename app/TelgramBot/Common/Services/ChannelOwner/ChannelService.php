<?php


namespace App\TelgramBot\Common\Services\ChannelOwner;


use App\TelgramBot\Common\GeneralService;
use App\TelgramBot\Database\ChannelRepository;

/**
 * Class ChannelService
 * @package App\TelgramBot\Common\Services\ChannelOwner
 */
class ChannelService
{
    /**it checks if the channel is owned by the coming chat
     * @param $channel_id
     * @param $chat_id
     * @return bool
     */
    public static function channelAuthorization($channel_id, $chat_id):bool
  {
      if (ChannelRepository::checkChannelbyIDAndChatId($channel_id,$chat_id))
          return true;
      return false;
  }

    /**it checks per day post call back query
     *
     */
    public static function checkIfCallBackQueryIsPerDayPost()
  {
      if (GeneralService::isQuery()) {
          if (GeneralService::checkStartString(GeneralService::getCallBackQueryData(), 'per_day_posts'))
              return true;
          return false;
      }
      return false;
  }

    /**
     * @return bool
     */
    public static function checkIfTheCommandISChangePerDayPost()
  {
      if (GeneralService::isQuery()) {
                if (GeneralService::checkStartString(GeneralService::getCallBackQueryData(), 'change_per_day_post'))
                    return true;
                return false;
      }
      return false;

  }

    /**
     * @param $message
     * @return string
     */
    public static function getChannelIdFromChangePerDayCommand($message):string
  {
      return GeneralService::findAfterString($message,'/change_per_day_post_');
  }

    /**
     *
     */
    public static function checkIfCallBackQueryIsPostHistory()
    {
        if (GeneralService::isQuery()) {
            if (GeneralService::checkStartString(GeneralService::getCallBackQueryData(), 'post_history'))
                return true;
            return false;
        }
        return false;
    }
}
