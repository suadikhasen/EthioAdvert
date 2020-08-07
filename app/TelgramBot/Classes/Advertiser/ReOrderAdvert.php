<?php

namespace App\TelgramBot\Classes\Advertiser;

use App\EthioAdvertPost;
use App\TelgramBot\Common\GeneralService;
use App\TelgramBot\Common\Services\Advertiser\ReOrderAdvertService;
use App\TelgramBot\Object\Chat;
use Carbon\Carbon;
use DemeterChain\C;
use Telegram\Bot\Exceptions\TelegramSDKException;

class ReOrderAdvert extends ViewAdverts
{
    private $last_asked_question;

    /**
     * @param bool $isCommand
     * @throws TelegramSDKException
     *
     */
    public function handle($isCommand = false)
   {
        if (ReOrderAdvertService::canBeReOrdered($this->advert->id))
          GeneralService::answerCallBackQuery('you can not re order may be it is pending or there is another pending advert');
        elseif ($isCommand)
         $this->processCommand();
        else
         $this->processQuestion(Chat::lastAskedQuestion());
   }

    /**
     *process command
     */
    protected function processCommand()
   {
      Chat::createQuestion('re_order_advert','initial_date');
      ReOrderAdvertService::putReOrderAdvertIdCache($this->advert->id);
      GeneralService::sendInitialDateMessage();
      GeneralService::answerCallBackQuery('re order started');
   }

    /**
     * @param $last_asked_question
     */
    protected function processQuestion($last_asked_question)
    {
        $this->last_asked_question = $last_asked_question;
        if ($last_asked_question->answer === null)
        {
            if ($last_asked_question->question === 'initial_date'){
                 $this->acceptInitialDate();
            }elseif ($last_asked_question->question === 'final_date'){
              $this->acceptFinalDate();
            }
        }
    }

    protected function acceptInitialDate()
    {
        if (GeneralService::validateInitialDate(Chat::$text_message)){

            Chat::createAnswer($this->last_asked_question->id);
            Chat::createQuestion('re_order_advert','final_date');
            GeneralService::sendFinalDateMessage();
        }
    }

    protected function acceptFinalDate()
    {
       if (GeneralService::validateFinalDate(Chat::$text_message)){
           $this->insert();
           Chat::sendTextMessage('Re Ordered SuccessFully'."\n".'you can change other values by using my promotions button');
       }
    }

    protected function insert()
    {
        $initial_date = Carbon::parse(ReOrderAdvertService::getInitialDateForReOrder());
        $final_date = Chat::$text_message;
        $total_price = GeneralService::checkTotalPriceOfAdvert($this->advert->no_view);
       EthioAdvertPost::create([
           'advertiser_id'           =>   Chat::$chat_id,
           'text_message'            =>   $this->advert->text_message,
           'image_path'              =>   $this->advert->image_path,
           'initial_date'            =>   $initial_date,
           'final_date'              =>   $final_date,
           'no_view'                 =>   $this->advert->no_view,
           'name_of_the_advert'      =>   $this->advert->name_of_the_advert,
           'description_of_advert'   =>   $this->advert->description_of_advert,
           'payment_code'            =>   $this->advert->payment_code,
           'payment_per_view'        =>   GeneralService::per_view_price,
           'amount_of_payment'       =>   $total_price,
           'active_status'           =>   false,
           'approve_status'          =>   false,
           'payment_status'          =>   false,
       ]);
    }
}
