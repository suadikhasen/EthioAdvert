<?php

namespace App\TelgramBot\Common;

use App\TelgramBot\Database\BankRepository;
use App\TelgramBot\Database\ChannelRepository;
use App\TelgramBot\Database\UserPaymentRepositories;
use App\TelgramBot\Extend\UserInformation;
use App\TelgramBot\Object\Bank;
use App\TelgramBot\Object\Chat;
use phpDocumentor\Reflection\Types\Parent_;
use Telegram\Bot\Exceptions\TelegramSDKException;
use Telegram\Bot\Keyboard\Keyboard;
/**
 * Class Pages
 * @package App\TelgramBot\Common
 */
class Pages

{

    /**
     * @var \Telegram\Bot\Api
     */
    protected static $bot;
    /**
     * @var
     */
    protected static $keyboard;
    /**
     * @var string
     */
    public static $text;
    /**
     * @var
     */
    protected static $reply_markup;

    /**
     * Pages constructor.
     */
    public function  __construct()
    {
        self::$bot = Chat::$bot;
    }

    /**sends to the user start page when the start button is pressed or for unregistered users
     * @return bool
     */
    public static function startPage()
   {
       self::$keyboard = [
           [
               'text' => 'Advertiser'
           ],
           [
               'text' => 'ChannelOwner'
           ]
       ];
       self::$text = 'please register  by selecting one of those';
       return self::makeMarkUp()->sendMessage();

   }

    /**sends homepage for advertiser
     * @return void
     * @throws TelegramSDKException
     */
    public static function advertiserPage():void
   {
        self::$text = 'you are now on advertiser page';
        $markUp = Keyboard::make(['resize_keyboard' => true])->row(Keyboard::button([
            'text'=>'Add Promotion',
        ]),Keyboard::button([
            'text' =>'My Promotions'
        ]))->row(Keyboard::button([
            'text' =>'Verify Payment'
        ]),Keyboard::button([
            'text' =>'Help'
        ]));
        self::sendMessage($markUp);
   }

    /**sends homepage for channelOwner
     * @return void
     * @throws TelegramSDKException
     */
    public static function  channelOwnerPage():void
   {
       self::$text = 'You Are Now On HomePage';
       $markUp = Keyboard::make(['resize_keyboard' => true])->row(Keyboard::button([
           'text' => 'Channel'
       ]),Keyboard::button([
           'text' => 'Payment Method'
       ]))->row(Keyboard::button([
           'text' => 'Earning Report',
       ]),Keyboard::button([
           'text' => 'Paid Report',
       ]))->row(Keyboard::button([
           'text' => 'Posts',
       ]),Keyboard::button([
           'text' => 'Pending Payment',
       ]))->row(Keyboard::button([
           'text'  => 'Help'
       ]));
       self::sendMessage($markUp);
   }

    /**
     *
     */
    public static function makeMarkUp(): void
   {
       self::$reply_markup = Keyboard::make([
           'keyboard' => self::$keyboard,
           'resize_keyboard' => true,

       ])->row()->row();


   }

    /**
     * @param $reply_markup
     * @return mixed
     * @throws TelegramSDKException
     */
    public static function sendMessage($reply_markup)
   {
       $response = Chat::$bot->sendMessage([
           'chat_id'         =>   Chat::$chat_id,
           'text'            =>   self::$text,
           'parse_mode'      =>  'HTML',
           'reply_markup'    =>   $reply_markup,
           'disable_notification' => false,
       ]);

   }

    /**sends channel homepage
     * @return void
     * @throws TelegramSDKException
     */
    public static function ChannelPage():void
    {
        $markup = Keyboard::make([
            'resize_keyboard' => true,
        ])->row(Keyboard::button( [
            'text' => 'Add Channel'
        ]),Keyboard::button( [
            'text' => 'All Channel'
        ]))->row(Keyboard::button([
            'text' => 'Remove Channel'
        ]),Keyboard::button([
            'text' => 'Main Menu'
        ]));

        self::$text='you are now on channels option';
        self::sendMessage($markup);

    }

    /**
     * @param $message
     * @throws TelegramSDKException
     */
    public static function textMessageWithMenuButton($message): void
    {
         $markup = Keyboard::make(['resize_keyboard' => true])->row(Keyboard::button([
             'text' => 'Main Menu'
         ]));
         self::$text = $message;
         self::sendMessage($markup);
    }

    /**
     * @throws TelegramSDKException
     */
    public static function earningPage(): void
    {
        self::$text = 'Earning Option Page';
        $markUp = Keyboard::make(['resize_keyboard' => true])->row(Keyboard::button([
            'text' => 'Total Earning'
        ]),Keyboard::button([
            'text' => 'Monthly Earning'
        ]))->row(Keyboard::button([
            'text' => 'Today Earning'
        ]),Keyboard::button([
            'text' => 'Main Menu'
        ]));
        self::sendMessage($markUp);
    }

    /**
     * @throws TelegramSDKException
     */
    public static function listOfChannelsPage(): void
    {
       $list_of_channels = ChannelRepository::allChannelsOfAuser();
       $mark_up  = Keyboard::make(['resize_keyboard' => true]);
       foreach ($list_of_channels as $single){
           $mark_up =  $mark_up->row(Keyboard::button([
               'text' => $single->name
           ]));
       }
        Chat::createQuestion('All_Channel','click_channel_name');
        $mark_up = $mark_up->row(Keyboard::button(['text' => 'Main Menu']));
        self::$text = 'click channels name button to see more about the channels';
        self::sendMessage($mark_up);
    }

    public static function removeChannelsPage()
    {
        $list_of_channels = ChannelRepository::allChannelsOfAuser();
        $mark_up  = Keyboard::make(['resize_keyboard' => true]);
        foreach ($list_of_channels as $single){
            $mark_up =  $mark_up->row(Keyboard::button([
                'text' => $single->name
            ]));
        }
        $mark_up = $mark_up->row(Keyboard::button(['text' => 'Main Menu']));
        self::$text = 'click channels name button to remove the channel';
        self::sendMessage($mark_up);
    }


    /**
     *sends user payment method page
     *
     * @return void
     * @throws TelegramSDKException
     */
    public static function usersPaymentMethodPage(): void
    {
       $payment_detail = UserPaymentRepositories::userPaymentMethod();
       self::$text = '<strong>Payment Method Information</strong>'."\n".
       '<strong>Payment Method:</strong>'.BankRepository::getBankInformation($payment_detail->bank_code)->bank_name."\n".
       '<strong>Full Name:</strong>'.$payment_detail->full_name."\n".
       '<strong>Account Number:</strong>'.$payment_detail->account_number
       ;
       $mark_up = Keyboard::make(['resize_keyboard' => true])->row(Keyboard::button([
           'text' => 'Change Payment Method'
       ]))->row(Keyboard::button([
           'text' => 'Main Menu'
       ]));
       self::sendMessage($mark_up);
    }

    /**pages that will be displayed for users to add new payment method
     * @param $add_type
     * @return void
     * @throws TelegramSDKException
     */
    public static function addNewPaymentMethodPage($add_type): void
    {
        if ($add_type === 'add') {
            Chat::createQuestion('payment_method','bank_code');
            self::$text = '⛔️<b>you have no payment Method </b>'."\n".'➕<b>select your payment method to add new payment method </b>';
        }else{
            Chat::createQuestion('change_payment_method','bank_code');
            self::$text = 'Select Your Payment Method To Change Payment Method';
        }
      self::paymentMethodPage();
    }

    /**
     * @throws TelegramSDKException
     */
    public static function paymentMethodPage(): void
    {
        $list_of_payment_method = BankRepository::getAllBank();
        if($list_of_payment_method->isEmpty()){
            Chat::sendTextMessage('no available payment method now');
        }else{
            $mark_up = Keyboard::make(['resize_keyboard' => true]);
            foreach ($list_of_payment_method as  $method){
                $mark_up = $mark_up->row(Keyboard::button([
                    'text' => $method->bank_name
                ]));
            }
            $mark_up = $mark_up->row(Keyboard::button([
                'text' => 'Main Menu'
            ]),Keyboard::button([
                'text' => 'Back'
            ]));
            self::sendMessage($mark_up);
        }
        
    }


    public static function postsPage()
    {
      self::$text = 'you are now on posts page';
      $reply_mark_up = Keyboard::make(['resize_keyboard' => true])->row(
       Keyboard::button([
          'text' => 'Per Day Post'
      ]),Keyboard::button([
          'text' => 'Post History'
      ]))->row(Keyboard::button([
          'text' => 'Main Menu'
      ]));
      self::sendMessage($reply_mark_up);
    }



}
