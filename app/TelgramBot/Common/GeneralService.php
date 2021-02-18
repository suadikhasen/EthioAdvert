<?php


namespace App\TelgramBot\Common;


use App\EthioAdvertPost;
use App\TelgramBot\Database\AdvertsPostRepository;
use App\TelgramBot\Database\ChannelRepository;
use App\TelgramBot\Object\Chat;
use App\Temporary;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Telegram\Bot\Exceptions\TelegramSDKException;
use Andegna\DateTime;

/**
 * Class GeneralService
 * @package App\TelgramBot\Common
 */
class GeneralService
{

    /**
     *
     */
    public const default_number_of_view_advert_page = 2;
    /**
     *
     */
    public const default_number_of_view = 1000;
    /**
     *
     */
    public const per_view_price = 0.1;
    const default_number_of_posts = 1;

    

    public static function assignChatValues()
    {
        Chat::$update = Chat::$bot->commandsHandler(true);
        if (!Chat::$update->isType('callback_query')) {
            Chat::$chat = Chat::$update->getMessage()->getChat();
            Chat::$text_message = Chat::$update->getMessage()->getText();
            Chat::$message = Chat::$update->getMessage();
            Chat::$chat_id = Chat::$update->getMessage()->getChat()->getId();
        } else{
            Chat::$chat_id = GeneralService::getChatIdFromCallBack();
        }
    }

    /**
     *
     */
    public static function calculateMaximumView()
   {
     return ChannelRepository::totalMembersOfAllChannel() /2;
   }

    /**
     * @param $number_of_view
     * @return float
     */
    public static function checkTotalPriceOfAdvert($number_of_view): float
    {
      return ($number_of_view*self::per_view_price);
    }

    /**
     * @return bool
     */
    public static function checkCallBackQuery(): bool
   {
     if (Chat::$update->isType('callback_query'))
     {
         return true;
     }
     return false;
  }

    /**
     * @return string
     */
    public static function getCallBackQueryData(): string
  {
     return Chat::$update->getCallbackQuery()->getData();
  }

    /**
     * @return string
     */
    public static function getInlineMessageID(): string
  {
      return Chat::getCallbackQuery()->getInlineMessageId();
  }

    /**
     * @return int
     */
    public static function getChatIdFromCallBack(): int
  {
      return Chat::getCallbackQuery()->getFrom()->getId();
  }

    /**
     * @return int
     */
    public static function getMessageIDFromCallBack(): int
  {
      return Chat::getCallBackQuery()->getMessage()->getMessageId();
  }

    /**
     * @return int
     */
    public static function getCallBackQueyId()
  {
      return Chat::getCallBackQuery()->getId();
  }

    /**
     * @param null $text_message
     * @throws TelegramSDKException
     */
    public static function answerCallBackQuery($text_message=null,$alert=false)
    {
        Chat::$bot->answerCallbackQuery([
            'callback_query_id' => self::getCallBackQueyId(),
            'text'              => $text_message,
            'show_alert'        => $alert
        ]);
    }



    /**check if advert is available for the given days
     * @param $initial_date
     * @param $final_date
     * @return bool
     */
    public static function isFreeSpaceAvailable(Carbon $initial_date ,Carbon $final_date): bool
   {
     $maximum_posts = self::calculateMaximumPosts($initial_date,$final_date);
     $future_posts =  AdvertsPostRepository::numberOfPosts($initial_date,$final_date);
     if ($future_posts >= $maximum_posts && $maximum_posts > 0 && $future_posts > 0){
         return false;
     }
     return true;
   }

    /**calculate maximum post between two days
     * @param $initial_date
     * @param $final_date
     * @return int
     */
    public static function calculateMaximumPosts(Carbon $initial_date, Carbon $final_date):int
   {
       $difference = $initial_date->diffInDays($final_date,true);
       return ($difference+1)*ChannelRepository::maximumPostsOfOneDay();
   }

    /**return page number from the coming call back querysss
     * @return int
     */
    public static function getPageNumberFromAdvertQuery():int
    {
        return (int) Str::after(self::getCallBackQueryData(),'/');
    }

    /**
     * @param $advertiser_id
     * @return bool
     */
    public static function isUnpaidAdvertAvailable($advertiser_id)
    {
      if (AdvertsPostRepository::checkExistenceOfNonExpiredUserAdvert($advertiser_id))
          return true;
      return false;
    }

    /**
     * @return bool
     */
    public static function checkAdvertPage()
    {
        if (Chat::$update->isType('callback_query'))
        {
            if (Str::startsWith(self::getCallBackQueryData(),'Page/'))
                return true;
            return false;
        }
        return false;
    }

    /*public static function getTextOfCallBackQuery()
    {
        if (self::isQuery())
          return Chat::getCallBackQuery()->getMessage()->getText();
        return false;
    }*/

    /**
     * @return bool
     */
    public static function isQuery()
    {
        if (Chat::$update->isType('callback_query')){
            return true;
        }
        return false;
    }

    /**
     * @param $string
     * @param $needle
     * @return bool
     */
    public static function checkStartString($string, $needle)
    {
        if (Str::startsWith($string,$needle))
            return true;
        return false;
    }

    /**
     * @param $string
     * @param $needle
     * @return string
     */
    public static function findAfterString($string, $needle)
   {
      return Str::after($string,$needle);
   }

    /**
     * @param $date
     * @return string
     */
    public static function findDayFromComingDate($date)
   {
       return  Str::before($date,'/');
   }

    /**
     * @param $date
     * @return string
     */
    public static function findMonthFromComingDate($date)
   {

      return Str::after($date,'/');
   }

    /**
     * @param $date
     * @return bool
     */
    public static function validateDate($date)
   {
       $short_day          = GeneralService::findDayFromComingDate($date);
       $short_month        = GeneralService::findMonthFromComingDate($date);
       $digit_string = '0123456789';
       if (strlen($short_day) < 2)
       {
           $short_day='0'.$short_day;
       }
       if (strlen($short_month) < 2)
       {
           $short_month='0'.$short_month;
       }
       $array_day = str_split($short_day);
       $array_month = str_split($short_month);
       if(Str::containsAll($digit_string,[$array_day[0],$array_day[1],$array_month[0],$array_month[1]]))
       {
           return true;
       }else{
           return false;
       }
   }

    /**
     * @param $date
     * @return bool
     * @throws TelegramSDKException
     */
    public static function validateInitialDate($date)
   {
       if (self::validateDate($date)){
           $initial_date   =  Carbon::create(Carbon::now()->year,self::findMonthFromComingDate($date),self::findDayFromComingDate($date));
           $next_day       =  Carbon::tomorrow();
           $day_difference =  $next_day->diffInDays($initial_date,false);
           if (($day_difference >= 0))
           {
               return true;
           }else{
               Chat::sendTextMessage('Your Date Must Be at least 1 day differ from this day');
               return false;
           }
       }else{
           Chat::sendTextMessage('please send correct date format as shown before');
           return false;
       }

   }

    /**
     * @param $date
     * @return bool
     * @throws TelegramSDKException
     */
    public static function validateFinalDate($date)
   {
       if (self::validateDate($date)){
           $final_date   =  Carbon::create(Carbon::now()->year,self::findMonthFromComingDate($date),self::findDayFromComingDate($date));
           $initial_date =  Carbon::parse(self::getInitialDateForEditing()->answer);
           return self::processFinalAndInitialDate($initial_date,$final_date);
       }else{
           Chat::sendTextMessage('please send correct date format as shown before');
           return false;
       }
   }

    /**
     * @param Carbon $initial_date
     * @param Carbon $final_date
     * @return bool
     * @throws TelegramSDKException
     */
    public static function processFinalAndInitialDate(Carbon $initial_date, Carbon $final_date): bool
    {
        if ($initial_date->diffInDays($final_date,false) < 0){
            Chat::sendTextMessage('Final Date Must Be Greater Than Or Equal To Initial Date');

            return false;
        }else{
          return  self::processPostAvailability($initial_date,$final_date);
        }
    }

    /**
     * @param Carbon $initial_date
     * @param Carbon $final_date
     * @return bool
     * @throws TelegramSDKException
     */
    public static function processPostAvailability(Carbon $initial_date, Carbon $final_date): bool
    {
        if (GeneralService::isFreeSpaceAvailable($initial_date,$final_date))
        {
            Chat::$text_message = $final_date;
            return  true;
        }else{
            Chat::sendTextMessage('Advert Not Available Please try another final date');
            return false;
        }
    }

    /**
     * @return mixed
     */
    public static function getInitialDateForEditing()
   {
       return Temporary::where('chat_id',Chat::$chat_id)->where('type','Edit Date')->where('question','initial_date')->first();
   }
    /**
     * @param bool $skip
     * @throws TelegramSDKException
     */
    public static function sendInitialDateMessage($skip=false)
   {
       Chat::sendTextMessage(self::makeDateMessage('initial date',$skip));
   }

    /**
     * @param bool $skip
     * @throws TelegramSDKException
     */
    public static function sendFinalDateMessage($skip = false)
   {
       Chat::sendTextMessage(self::makeDateMessage('final date',$skip));
   }


    /**
     * @param string $dateType
     * @param bool $skip
     * @return string
     */
    public static function makeDateMessage(string $dateType, bool $skip = false)
   {

     $main_message = 'Please Send The '.$dateType.' On Which The advert is Posted like'."\n".
         '<strong>DD/MM'."\n\n\n".'the date must be in Gregorian Calendar</strong>'."\n";
     if ($skip){
         $skip_string = 'or click /skip to skip this';
         return $main_message.$skip_string;
     }

     return $main_message;
   }

    /**
     * @param $date
     * @return Carbon
     */
    public static function makeInitialAndFinalDate($date)
   {
      return Carbon::create(Carbon::now()->year,self::findMonthFromComingDate($date),self::findDayFromComingDate($date));
   }


   public static function getEthiopianCurrentyear()
   {
       $ethiopian_date = new DateTime();
       return  $ethiopian_date->getYear();
   }

   public static function getEthiopianCurrentHour()
   {
       return (new DateTime())->getHour();
   }

   

   private function channelMenus()
   {
       return [
           'Channel',
           'Payment Method',
           'Earning Report',
           'Paid Report',
           'Posts',
           'Remove Channel',
           'Add Channel',
           'Per Day Post',
           'Post History',
           'Main Menu',
           'Back',
           'Change Payment Method',
           'Monthly Earning',
           'Today Earning',
           'Total Earning',
           'All Channel'
       ];
   }
   
   /**
    * @param $page_number,$per_page
    * @return int 
    */
   public static function orderNumber($page_number,$per_page):int
    {
        return (int) ((($page_number-1)*$per_page)+1);
    }




}
