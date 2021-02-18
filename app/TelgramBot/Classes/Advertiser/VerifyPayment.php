<?php


namespace App\TelgramBot\Classes\Advertiser;


use App\EthioAdvertPost;
use App\PaymentVerification;
use App\TelgramBot\Common\GeneralService;
use App\TelgramBot\Database\AdvertsPostRepository;
use App\TelgramBot\Database\PaymentVerificationRepository;
use App\TelgramBot\Object\Chat;
use Illuminate\Support\Facades\DB;
use Telegram\Bot\Exceptions\TelegramSDKException;
use App\listOfPaymentMethod;
use App\TelgramBot\Common\Services\Advertiser\EditAdvertService;
use App\TelgramBot\Common\Services\Advertiser\ViewAdvertService;
use App\TelgramBot\Database\TemporaryRepository;
use App\TransactionNumbers;
use Telegram\Bot\Keyboard\Keyboard;

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
    private $keyboard;
    
    /**
     * @throws TelegramSDKException
     */
    public function sendPaymentMethod()
    {
        if (GeneralService::isUnpaidAdvertAvailable(Chat::$chat_id))
        {
            Chat::createQuestion('Verify_Payment','list_of_payment_method');
            $this->paymentMethodKeyboard();
            Chat::sendTextMessageWithInlineKeyboard('select your payment method',$this->keyboard);
        }
        else
            Chat::sendTextMessage('❗️<b>You dont have pending Advert Or your advert is expired. </b>');
    }

    private function paymentMethodKeyboard()
    {
       $payment_methods = listOfPaymentMethod::get();
       $this->keyboard = Keyboard::make()->inline();
       foreach($payment_methods as $payment_method){
           $this->keyboard = $this->keyboard->row([
               'text'           => $payment_method->bank_name,
               'callback_data'  => 'select_advertiser_payment_method/'.$payment_method->id
           ]);
       }
    }

    public function selectPaymentMethod()
    {
        if(EditAdvertService::checkInlineKeyboard('select_advertiser_payment_method')){
            Chat::$text_message = ViewAdvertService::getIDFromViewKeyboard();
            GeneralService::answerCallBackQuery('we get your paymet method');
            Chat::sendEditTextMessage('⬇️ <b> please send ref number(transaction number) of your reciept. </b>',null,Chat::$chat_id,GeneralService::getMessageIDFromCallBack());
            Chat::createAnswer(Chat::lastAskedQuestion()->id);
            Chat::createQuestion('Verify_Payment','ref_number');
        }else{
            Chat::sendTextMessage('please use the given keyboard');
        }
    }

    public function acceptRefNumber()
    {
        if (AdvertsPostRepository::havePendingInformation(Chat::$chat_id)){
            $payment_code = TemporaryRepository::getSingleTemporaryData(Chat::$chat_id,'Verify_Payment','list_of_payment_method')->answer;
            $payment = PaymentVerificationRepository::isRefAvailable(Chat::$text_message,$payment_code);
            $advert  = AdvertsPostRepository::pendingPromotion(Chat::$chat_id);
            $this->post_id = $advert->id;
            if (!$payment){
                Chat::sendTextMessage('❗️ <b> invalid ref number please insert properly.</b>');
            }elseif($payment->amount >= $advert->amount_of_payment)
            {   $this->updatePaymentStatus();
                Chat::deleteTemporaryData();
                Chat::sendTextMessage('✅ <b> Your Payment Is Verified Successfully !!.</b>');
            }else{
                Chat::sendTextMessage('Your Amount Is Less than The Amount Of The Advert');
            }
        }else{
            Chat::sendTextMessage(' ❗️ <b> your advert is not approved.</b>');
        }

    }

    private function updatePaymentStatus()
    {
        DB::transaction(function (){

            EthioAdvertPost::where('id',$this->post_id)->update([
                'payment_status' => true,
                'active_status'  => 2,
            ]);

            TransactionNumbers::where('ref_number',Chat::$text_message)->update([
                'used_status'     =>   true,
            ]);

        },5);
    }
}
