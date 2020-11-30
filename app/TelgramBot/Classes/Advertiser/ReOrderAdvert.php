<?php

namespace App\TelgramBot\Classes\Advertiser;

use App\EthioAdvertPost;
use App\TelgramBot\Common\GeneralService;
use App\TelgramBot\Common\Services\Advertiser\ReOrderAdvertService;
use App\TelgramBot\Database\AdvertsPostRepository;
use App\TelgramBot\Object\Chat;
use Carbon\Carbon;
use DemeterChain\C;
use Telegram\Bot\Exceptions\TelegramSDKException;

class ReOrderAdvert extends ViewAdverts
{
    private $last_asked_question;
    private $can_not_order_message = 'you can not re order the advert'."\n"."\n".
    'ther may be on of two cases '."\n".
    '1,ther is another pending advert '."\n".
    '2,it is in pending ';

    /**
     * @param bool $isCommand
     * @throws TelegramSDKException
     *
     */
    public function handle($isCommand = false)
   {
        if (ReOrderAdvertService::canBeReOrdered($this->advert->id))
          GeneralService::answerCallBackQuery($this->can_not_order_message,true);
        else{

        }
       
   }

    /**
     *process command
     */
    protected function reOrder()
   {
    $this->advert->replicate()->fill([
        're_order_status'  =>   true,
        'approve_status'   =>   false,
        'payment_status'   =>   false,
        'active_status'    =>   false,

    ]);
    GeneralService::answerCallBackQuery('advert re order successfully'."\n".'You can change package information using edit adver option found in My Promotion Keyboard',true);
   }


}
