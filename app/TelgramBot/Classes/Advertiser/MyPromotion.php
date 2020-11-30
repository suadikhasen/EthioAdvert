<?php


namespace App\TelgramBot\Classes\Advertiser;

use App\TelgramBot\Common\Classes\PaginationkeyBokard;
use App\TelgramBot\Common\GeneralService;
use App\TelgramBot\Database\AdvertsPostRepository;
use App\TelgramBot\Object\Chat;
use Telegram\Bot\Exceptions\TelegramSDKException;
use Telegram\Bot\Keyboard\Keyboard;

/**
 * Class MyPromotion
 * @package App\TelgramBot\Classes\Advertiser
 */
class MyPromotion
{

    /**
     * @var null
     */
    protected $keyboard = null;
    /**
     * @var int
     */
    protected $page_number = 1;
    /**
     * @var
     */
    protected $list;
    /**
     * @var string
     */
    protected $text='<strong>---Your Advert Information -----</strong>'."\n".'----- <i>click view more to see more about the advert</i>  ---'."\n";
    /**
     * @var false|resource
     */
    private $ch;


    /**handle processes for my promotion keyboard and after it,when the request come
     * @param bool $isCommand
     * @throws TelegramSDKException
     */
    public  function handle(bool $isCommand=false): void
    {
      if ($isCommand)
          $this->sendListOfPromotions(false);
      else{
         $this->page_number = GeneralService::getPageNumberFromAdvertQuery();
         $this->sendListOfPromotions(true);
      }
    }

    /**
     * @param bool $query
     * @throws TelegramSDKException
     */
    private function sendListOfPromotions(bool $query)
    {
        $this->fetchData($this->page_number);
        $this->makeTextForListOfPromotions($this->list);
        $this->makeKeyBoardForListOfPromotions($this->list);
        $this->sendMessage($query);
    }

    /**
     * @param bool $query
     * @throws TelegramSDKException
     */
    private function sendMessage(bool  $query)
    {
        if ($query){
            Chat::sendEditTextMessage($this->text,$this->keyboard,GeneralService::getChatIdFromCallBack(),GeneralService::getMessageIDFromCallBack());
            GeneralService::answerCallBackQuery('Page Displayed');
        }else{
            Chat::sendTextMessageWithInlineKeyboard($this->text,$this->keyboard);
        }

    }

    /**
     * @param $page_number
     */
    public function fetchData($page_number)
    {
       $this->list = AdvertsPostRepository::promotionsOfUser(Chat::$chat_id,$page_number,GeneralService::default_number_of_view_advert_page);
       $this->list = json_decode($this->list,false);
    }

    /**
     * @param $list
     */
    private function makeTextForListOfPromotions($list)
    {
//      $list = json_decode($list,false);
      $no = $this->advertNumbers();
        foreach ($list->data as $advert){
            $this->text = $this->text.'<strong>'.$no.', '.$advert->name_of_the_advert.'</strong>'."\n".
                '<strong>price:</strong>'.$advert->amount_of_payment."\n".
                '/view_more_'.$advert->id."\n\n";
              $no++;
        }

    }

    /**
     * @param $list
     */
    private function makeKeyBoardForListOfPromotions($list)
    {
       $this->keyboard  =  (new PaginationkeyBokard(null,'Next',
       'Previous',
       'Page/'.($this->page_number+1),
       'Page/'.($this->page_number-1),
       $this->list->next_page_url,
       $this->list->prev_page_url))->makeInlinekeyboard();
    }

    /**
     * @return float|int
     */
    private function advertNumbers()
    {
        return (($this->page_number-1)*GeneralService::default_number_of_view_advert_page)+1;
    }

}
