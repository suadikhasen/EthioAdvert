<?php


namespace App\TelgramBot\Classes\Advertiser\EditAdvert;


use App\EthioAdvertPost;
use App\TelgramBot\Common\GeneralService;
use App\TelgramBot\Common\Services\Advertiser\EditAdvertService;
use App\TelgramBot\Database\AdvertsPostRepository;
use App\TelgramBot\Database\TemporaryRepository;
use App\TelgramBot\Object\Chat;
use App\Temporary;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class EditDate
{
   protected $advert_id;
   protected $initial_date;
   protected $final_date;
   protected $isCommand;
   protected $last_activity;
    /**
     * @var string
     */
    private $month;
    /**
     * @var string
     */
    private $day;

    public function __construct(int $advert_id, bool $isCommand=false)
   {
       $this->advert_id = $advert_id;
       $this->isCommand = $isCommand;
       if(!EditAdvertService::validateForEditing($this->advert)){
        exit;
     }
   }

   public function handle()
   {
       if ($this->isCommand)
           $this->handleCommand();
       else
           $this->handleQuestion(Chat::lastAskedQuestion());
   }

    private function handleCommand()
    {
        Chat::createQuestion('Edit Date','initial_date');
        EditAdvertService::putCacheForAdvertId($this->advert_id);
        GeneralService::sendInitialDateMessage(true);
        GeneralService::answerCallBackQuery('editing date started');
    }

    public function handleQuestion($activity,$skip = false)
    {
      $this->last_activity = $activity;
      if ($activity->question === 'initial_date')
      {
          if (GeneralService::validateInitialDate(Chat::$text_message)){
              Chat::createAnswer($activity->id);
              Chat::createQuestion('Edit Date','final_date');
              GeneralService::sendFinalDateMessage(true);
          }
      }else{
          if ($skip){
              $this->finishEditing();
          }elseif(GeneralService::validateFinalDate(Chat::$text_message)){
              $this->finishEditing();
          }
      }
    }

  public function finishEditing()
  {
      $this->assignDateValue();
      $this->update();
      EditAdvertService::removeCacheEditAdvertId();
      EditAdvertService::removeCacheAdvert($this->advert_id);
      Chat::deleteTemporaryData();
      Chat::sendTextMessage('Date Updated Successfully');
  }

  public  function assignDateValue()
  {
      $this->final_date = Chat::$text_message;
      if (TemporaryRepository::findPreviousOfLastActivity()->answer === '/skip'){
          $this->initial_date = AdvertsPostRepository::findAdvert($this->advert_id)->initial_date;
      }else{
          $this->initial_date = Carbon::parse(GeneralService::getInitialDateForEditing()->answer);
      }
  }

   public function update()
   {
      EthioAdvertPost::where('id',$this->advert_id)->update([

          'initial_date'  => $this->initial_date,
          'final_date'    => $this->final_date,
      ]);
   }

}
