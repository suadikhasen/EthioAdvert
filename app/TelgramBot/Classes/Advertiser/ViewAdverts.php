<?php


namespace App\TelgramBot\Classes\Advertiser;


use App\TelgramBot\Common\GeneralService;
use App\TelgramBot\Database\AdvertsPostRepository;
use App\TelgramBot\Object\Chat;
use Illuminate\Support\Facades\Cache;
use Telegram\Bot\Keyboard\Keyboard;

class ViewAdverts
{

    protected $advert;
    protected $advert_keyboards;
    protected $advert_text_message;

    function __construct($advert_id)
    {
        $this->advert = AdvertsPostRepository::findAdvert($advert_id);
        if($this->advert == null){
            exit;
        }
    }

    public function sendListOfOptions($edit=false)
   {
      $this->makeTextMessage();
      $this->makeMainKeyboard();
      $image_path = $this->advert->image_path;
       if ($edit && is_null($image_path)){
           Chat::sendEditTextMessage($this->advert_text_message,$this->advert_keyboards,GeneralService::getChatIdFromCallBack(),GeneralService::getMessageIDFromCallBack());
       }else{
           if(!is_null($image_path) && $edit){
               Chat::deleteMessage(Chat::$chat_id,GeneralService::getMessageIDFromCallBack());
           } 
           Chat::sendTextMessageWithInlineKeyboard($this->advert_text_message,$this->advert_keyboards);
       }
   }

    protected function makeTextMessage()
    {
      $this->advert_text_message = Cache::remember('advert_text'.$this->advert->id,now()->addMonths(2),function (){
         return $this->textMessage();
      });
    }

    private function textMessage()
    {
       return  
       '⟹ <b> View More Page</b>'."\n\n".
       '⇒ <strong> Id : </strong>'.$this->advert->id."\n\n".
       '⇒ <strong> Name : </strong>'.$this->advert->name_of_the_advert."\n\n".
       '⇒ <strong> price: </strong>'.$this->advert->amount_of_payment."\n\n";
             
    }

    private function makeMainKeyboard()
    {
        $this->advert_keyboards = Cache::remember('main_advert_keyboard'.$this->advert->id,now()->addMonths(2),function (){
            return $this->mainKeyboard();
        });
    }

   private function mainKeyboard()
   {
       return Keyboard::make()->inline()->row(Keyboard::inlineButton([
           'text'           => 'View Detail',
           'callback_data'  => 'view_advert_info/'.$this->advert->id
       ]))->row(Keyboard::inlineButton([
           'text'           => 'Delete Advert',
           'callback_data'  => 'delete_advert/'.$this->advert->id
       ]));
   }


}
