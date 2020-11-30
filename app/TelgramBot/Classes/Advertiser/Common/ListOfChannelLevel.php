<?php

namespace  App\TelgramBot\Classes\Advertiser\Common;
use  App\TelgramBot\Common\Classes\PaginationkeyBokard;
use App\TelgramBot\Database\LevelOfChannelReopoksitory;
use App\TelgramBot\Object\Chat;
use Telegram\Bot\Keyboard\Keyboard;
use App\TelgramBot\Common\GeneralService;

class  ListOfChannelLevel

{  
    private  $response;
    private  $text_message = 'please select level of channel in which the advert is posted.'."\n".'channels are levelled based on thier quality and thier poksts view'."\n";
    private  $level_key_board;
    private  $page_number;
    private  $list_of_levels;
    private  $all_keyboard;


   public function sendListOfLevel($page_number=1,$inline=false)
   {
    $this->page_number = $page_number;
    $this->assignValues();
    $this->makeLevelKeyboard();  
    $this->makePaginationkeyboard();
    if(!$inline){
        Chat::sendTextMessageWithInlineKeyboard($this->text_message,$this->all_keyboard);
       }else{
           Chat::sendEditTextMessage($this->text_message,$this->all_keyboard,Chat::$chat_id,GeneralService::getInlineMessageID());
       }
   }

   private function makePaginationkeyboard()
    {
        $this->all_keyboard = (new PaginationkeyBokard($this->level_key_board,
        'Next',
        'Previous',
        'level_keyboard_page/'.($this->page_number+1),
        'level_keyboard_page/'.($this->page_number-1),
        $this->list_of_levels->next_page_url,
        $this->list_of_levels->prev_page_url,
    ))->makeInlinekeyboard();

    }

    private function assignValues()
    {
       $this->list_of_levels =json_decode(LevelOfChannelReopoksitory::getPaginatedLevel(4,$this->page_number)->toJson());
       
    }

    private function makeLevelKeyboard()
    {  
        $this->level_key_board = Keyboard::make()->inline();
        foreach($this->list_of_levels as $level){
            $this->level_key_board=$this->level_key_board->row(Keyboard::inlineButton([
                'text'            => $level->level_name,
                'call_back_data'  => 'select_level/'.$level->id
            ]));
        }
    }
    
}