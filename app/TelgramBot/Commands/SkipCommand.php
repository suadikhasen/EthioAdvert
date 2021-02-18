<?php


namespace App\TelgramBot\Commands;

use App\TelgramBot\Classes\Advertiser\EditAdvert\EditDate;
use App\TelgramBot\Common\GeneralService;
use App\TelgramBot\Database\TemporaryRepository;
use App\TelgramBot\Database\AdvertsPostRepository;
use App\TelgramBot\Object\Chat;
use Illuminate\Support\Facades\Cache;
use Telegram\Bot\Commands\Command;

class SkipCommand extends Command
{
    protected  $name = 'skip';


    /**
     * {@inheritdoc}
     */
    public function handle()
    {
        GeneralService::assignChatValues();
        $last_activity = Chat::lastAskedQuestion();
      if ($last_activity->question === 'image_path')
      {
          $text = 'Please Send The Initial Date On Which The advert is Posted like' . "\n" .
          '<b> DD/MM' . "\n\n\n".'</b>'.
          '<b> example if you want your initial date to be january 21 your input will be 01/21 </b>'."\n".
          'the date must be in European Calandar </strong>';
           Chat::createQuestion('Add_Promotion','initial_date');     
           Chat::sendTextMessage($text);
      }elseif($last_activity->question === 'initial_date'){
          Chat::createAnswer($last_activity->id);
          Chat::createQuestion('Edit Date','final_date');
          GeneralService::sendFinalDateMessage(true);
      }elseif($last_activity->question === 'final_date'){
         if (TemporaryRepository::findPreviousOfLastActivity()->answer === '/skip'){
             Chat::deleteTemporaryData();
             Chat::sendTextMessage('nothing is changed');
         }else{
              Chat::$text_message = AdvertsPostRepository::findAdvert(Cache::get('edit_advert_id'))->final_date;
              (new EditDate(Cache::get('edit_advert_id'),true))->handleQuestion($last_activity,true);
         }
      }

        Chat::$isCommand = true;
    }
}
