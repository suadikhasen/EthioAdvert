<?php


namespace App\TelgramBot\Database;


use App\Channels;
use App\TelgramBot\Object\Chat;

/**
 * Class ChannelRepository
 * @package App\TelgramBot\Database
 */
class ChannelRepository
{
    /**
     * @return mixed
     */
    public static  function allChannelsOfAuser($chat_id)
   {
      return Channels::where('channel_owner_id',$chat_id)->where('remove_status',false)->get();
   }

   public static function updateRemoveStatus($channel_id,$status)
   {
      self::findChannel($channel_id)->update([
         'remove_status' => $status,
      ]);
   }

  public static function findByNameAndChatId($chat_id,$name)
  {
    return Channels::where('channel_owner_id',$chat_id)->where('name',$name)->first();
  }

    /**
     * @return mixed
     */
    public  static function checkExistenceOfAchannel()
  {
      return Channels::where('channel_owner_id',Chat::$chat_id)->where('remove_status',false)->exists();
  }

    /**
     * @param $name
     * @return mixed
     */
    public static function  searchChannelByName($name)
  {
        return Channels::where('channel_owner_id',Chat::$chat_id)->where('name',$name)->first();
  }

    /**remove channels of a user by searching name of channel
     * remove does not mean remove from table
     * only change remove status column
     * @param $name
     * @param $chat_id
     */
    public static function removeChannelsOfAuserByName($name, $chat_id)
  {
       Channels::where('channel_owner_id',Chat::$chat_id)->where('name',$name)->update([
           'remove_status' => true
       ]);
  }

    /**maximum posts in one day
     * @return int
     */
    public static function maximumPostsOfOneDay():int
  {
      return Channels::where([
          [
              'approve_status' ,true
          ],
          [
              'remove_status',false
          ]
      ])->sum('per_day_posts');
  }

    /**total number of members
     * @return int
     */
    public static function totalMembersOfAllChannel():int
  {
      return Channels::where('remove_status',false)->where('approve_status',true)->sum('number_of_member');
  }


    /**
     * @param int $id
     * @return mixed
     */
    public static function getChannelById(int $id)
    {
        return Channels::with(['user','level'])->findOrFail($id);
    }

    /**
     * @param $channel_id
     * @param $chat_id
     * @return bool
     */
    public static function checkChannelbyIDAndChatId($channel_id, $chat_id):bool
    {
        return Channels::where('channel_owner_id',$chat_id)->where('id',$channel_id)->exists();
    }
    
    public static function allChannel()
    {
       return Channels::simplePaginate(10);
    }

    public static function updateBlockStatus($id,$status)
    {
       Channels::find($id)->update([
         'block_status' => $status,
       ]);
    }

    public static function findChannel($channel_id)
    {
      return Channels::find($channel_id);
    }

}
