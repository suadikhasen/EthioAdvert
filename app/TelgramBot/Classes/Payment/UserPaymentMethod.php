<?php


namespace App\TelgramBot\Classes\Payment;


use App\TelgramBot\Common\Pages;
use App\TelgramBot\Database\BankRepository;
use App\TelgramBot\Database\UserPaymentRepositories;
use App\TelgramBot\Object\Bank;
use App\TelgramBot\Object\Chat;
use App\Temporary;
use App\UserPaymentMethod as PaymentMethod;
use Telegram\Bot\Exceptions\TelegramSDKException;

class UserPaymentMethod
{


    /**
     * @param bool $isCommand
     * @throws TelegramSDKException
     */
    public  function handle($isCommand=false)
   {
     if ($isCommand){
         if (is_user_have_Payment_method()){
             Pages::usersPaymentMethodPage();
         }else{
             Pages::addNewPaymentMethodPage('add');
         }

     } else{
       $this->processQuestion(Chat::lastAskedQuestion());
     }
   }

    /**it is used to handle answer without command and it accepts last asked question retrieved from database
     * @param $response
     * @throws TelegramSDKException
     */
    public  function processQuestion($response): void
    {
        if ($response->answer === null)
        {
            switch ($response->question){
                case 'bank_code':
                    Chat::createAnswer($response->id);
                    if (Chat::type() === 'payment_method'){
                        Chat::createQuestion('payment_method','full_name');
                    }else{
                        Chat::createQuestion('change_payment_method','full_name');
                    }
                    Chat::sendTextMessage('Please Insert Your Full Name');
                    break;
                case 'full_name':
                    Chat::createAnswer($response->id);
                    if (Chat::type() === 'payment_method'){
                        Chat::createQuestion('payment_method','account_number');
                    }else{
                        Chat::createQuestion('change_payment_method','account_number');
                    }
                    Chat::sendTextMessage('Please Insert Your Account Number');
                    break;
                case 'account_number':
                    Chat::createAnswer($response->id);
                    if (Chat::type() === 'change_payment_method'){
                        UserPaymentRepositories::deletePaymentMethod();
                    }
                    $this->addPaymentMethod();
                    Chat::sendTextMessage('Your Payment Method Added Successfully');
                    Pages::usersPaymentMethodPage();

            }
        }

    }

    /**used to add new payment method to database
     * @return void
     */
    private function addPaymentMethod():void
    {   $array = Array();
        $last_asked_questions = Temporary::where('chat_id',Chat::$chat_id)->where('type',Chat::type())->get();
        foreach ($last_asked_questions as $single){
            if ($single->question === 'bank_code'){
                $array['bank_code'] = BankRepository::getBankInformationByName($array['bank_code'])->id;
            }else{
                $array[(string)$single->question] = $single->answer;
            }


        }
        PaymentMethod::create([
          'chat_id'    => Chat::$chat_id,
           'full_name' => $array['full_name'],
           'bank_code' => $array['bank_code'],
           'account_number' => $array['account_number'],
        ]);
    }


}
