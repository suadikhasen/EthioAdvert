<?php

namespace App\TelgramBot\Common\Classes;
use Telegram\Bot\Keyboard\Keyboard;


class PaginationkeyBokard{
    /**
     * instance of keyboard it is required
     * because it may another inline keyboard with pagination
     * @var Keyboard  
     */
    private $instance_of_keyboard;

    /**
     * text for next inline keyboard
     * @var string
     */
    private $text_of_next_keyboard;

    /**
     * text for previous inline keyboard
     * @var string
     */
    private $text_of_previous_keyboard;

    /**
     * call back text for next inline keyboard
     * @var string
     */

    private $call_back_of_next_keyboard;

    /**
     * call back text for next inline keyboard
     * @var string
     */
    private $call_back_of_previous_keyboard;

    /**
     * url text to check the page existence
     * @var string
     */
    private $previous_page_url;

    /**
     * url text to check the page existence
     * @var string
     */
    private $next_page_url;

    public function __construct($instance_of_keyboard=null,$text_of_next_keyboard,$text_of_previous_keyboard,$call_back_of_next_keyboard,$call_back_of_previous_keyboard,$next_page_url,$previous_page_url)
    {
        $this->instance_of_keyboard           =    $instance_of_keyboard;
        $this->text_of_next_keyboard          =    $text_of_next_keyboard;
        $this->text_of_previous_keyboard      =    $text_of_previous_keyboard;
        $this->call_back_of_next_keyboard     =    $call_back_of_next_keyboard;
        $this->call_back_of_previous_keyboard =    $call_back_of_previous_keyboard;
        $this->next_page_url                  =    $next_page_url;
        $this->previous_page_url              =    $previous_page_url;
    }

    public function makeInlinekeyboard()
    {
        if($this->previous_page_url === null && $this->next_page_url !== null){
             $this->nextkeyboard();      
         }elseif($this->next_page_url === null && self::$previous_page_url !== null){
            $this->previouskeyboard();
         }elseif(self::$previous_page_url !== null && $this->next_page_url !== null){
            $this->bothKeyBoard();
         }
         return $this->instance_of_keyboard;
    }

    private  function nextkeyboard()
    {
      $this->instance_of_keyboard =  $this->instance_of_keyboard->row(Keyboard::inlineButton([
            'text'          => $this->text_of_next_keyboard,
            'callback_data' => $this->call_back_of_next_keyboard
          ]));
    }
   
    private function previouskeyboard()
    {
        $this->instance_of_keyboard =  $this->instance_of_keyboard->row(Keyboard::inlineButton([
            'text'          => $this->text_of_previous_keyboard,
            'callback_data' => $this->call_back_of_previous_keyboard,
          ]));
    }

    private  function  bothKeyBoard()
    {
        $this->instance_of_keyboard =  $this->instance_of_keyboard->row(
        Keyboard::inlineButton([
        'text'          => $this->text_of_previous_keyboard,
        'callback_data' => $this->call_back_of_previous_keyboard,
        ]),

        Keyboard::inlineButton([
            'text'          => $this->text_of_next_keyboard,
            'callback_data' => $this->call_back_of_next_keyboard
          ]));
    }
}