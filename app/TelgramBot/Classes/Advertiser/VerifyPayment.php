<?php


namespace App\TelgramBot\Classes\Advertiser;


use App\EthioAdvertPost;
use App\PaymentVerification;
use App\TelgramBot\Common\GeneralService;
use App\TelgramBot\Database\AdvertsPostRepository;
use App\TelgramBot\Database\PaymentVerificationRepository;
use App\TelgramBot\Object\Chat;
use App\Temporary;
use Illuminate\Support\Facades\DB;
use Telegram\Bot\Exceptions\TelegramSDKException;

/**
 * Class VerifyPayment
 * @package App\TelgramBot\Classes\Advertiser
 */
class VerifyPayment
{
    /**
     * @var
     */
    private $post_id;

    /**
     * @param bool $isCommand
     * @throws TelegramSDKException
     */
    public function handle(bool $isCommand)
    {
      if ($isCommand)
          $this->processCommand();
      else
          $this->processQuestion(Chat::lastAskedQuestion());
    }

    /**
     * @throws TelegramSDKException
     */
    private function processCommand()
    {
        if (GeneralService::isUnpaidAdvertAvailable(Chat::$chat_id))
        {
            Chat::createQuestion('Verify_Payment','ref_number');
            Chat::sendTextMessage('Please Send Ref Number Of Your Recipient');
        }
        else
            Chat::sendTextMessage('You dont have ordered Advert Or Your Advert Is Expired Please Add New Advert Or Update Your Advert');
    }

    /**
     * @param Temporary|null $lastAskedQuestion
     * @throws TelegramSDKException
     */
    private function processQuestion(?Temporary $lastAskedQuestion)
    {
       if (GeneralService::isUnpaidAdvertAvailable(Chat::$chat_id)){
           $payment = PaymentVerificationRepository::getPayment(Chat::$chat_id,Chat::$text_message);
           $advert  = AdvertsPostRepository::pendingPromotion(Chat::$chat_id);
           $this->post_id = $advert->id;
           if (!$payment){
               Chat::sendTextMessage('invalid ref number please insert properly');
           }elseif($payment->amount >= $advert->amount_of_payment)
           {   $this->updatePaymentStatus();
               Chat::sendTextMessage('Your Payment Is Verified Successfully');
           }else{
               Chat::sendTextMessage('Your Amount Is Less than The Amount Of The Advert');
           }
       }

    }

    private function updatePaymentStatus()
    {
        DB::transaction(function (){

            EthioAdvertPost::where('id',$this->post_id)->update([
                'payment_status' => true
            ]);

            PaymentVerification::where('ref_number',Chat::$text_message)->update([
                'used_status'     =>   true
            ]);

        },5);
    }
}
