<?php

namespace App\TelgramBot\Classes\Paid;

use App\Http\Resources\Paid as PaidResource;
use App\Http\Resources\PaidCollection;
use App\TelgramBot\Common\Pages;
use App\TelgramBot\Database\PaidRepository;
use App\Paid as PaidModel;
use App\TelgramBot\Object\Chat;
use Telegram\Bot\Exceptions\TelegramSDKException;
use Telegram\Bot\Keyboard\Keyboard;
use Illuminate\Support\Collection;

class PaidReport
{
    /**
     *used to handle paid report
     * @param $isCommand
     * @throws \Telegram\Bot\Exceptions\TelegramSDKException
     */
    public function handle($isCommand): void
    {

        if ($isCommand) {
            if (PaidRepository::checkExistenceOfPaidReport(Chat::$chat_id)){
                Chat::sendTextMessage('no paid history');
            } else {
                $paid_history = $this->paidHistory();
                $text = $this->textMessagePaid($paid_history);
                $this->sendPaidMessage($paid_history,$text);
            }

        } else{
            $response = Chat::lastAskedQuestion();
            $this->processQuestion($response);
        }
  }

  public function paidHistory()
  {
/*       return   (new PaidCollection(PaidRepository::userPaidReport(Chat::$chat_id)));*/
      return json_decode(PaidRepository::userPaidReport(Chat::$chat_id)->toJson(),false);
  }

  private function textMessagePaid($paid_history):string
  {
        $text = '';
        $pay_no = ($paid_history->current_page-1)*$paid_history->per_page;
        foreach ($paid_history->data as $single_paid) {
            $pay_no++;
            $text .= '<strong>Payment ' . $pay_no . '</strong>' . "\n" .
                '<strong>transaction id: ' . $single_paid->id . '</strong>' . "\n" .
                '<strong>Paid Amount ' . $single_paid->paid_amount . '</strong>' . "\n" .
                '<strong>Date: ' . $single_paid->created_at . '</strong>' . "\n" .
                '----------' . "\n" . "\n";
        }
        return $text;
  }

    private function processQuestion(?\App\Temporary $response): void
    {
        if ($response->answer === null){

            $opts = array(
                'http'=>array(
                    'method'=>"POST",

                )
            );
          $context = stream_context_create($opts);
          $result = file_get_contents(Chat::url.'?page='.Chat::getCallBackQuery()->getData(),true,$context);
          $paid_history = $result;
          $text = $this->textMessagePaid($paid_history);
          $this->sendPaidMessage($paid_history,$text);
        }else{
            Pages::textMessageWithMenuButton('something went wrong');
            Chat::deleteTemporaryData();
        }

    }

    private function sendPaidMessage($paid_history,$text):void
    {
        if ($paid_history->next_page_url === null){
            Chat::sendTextMessage($text);
        } else {
            $keyboard = Keyboard::make()->inline()->row(Keyboard::inlineButton([
                'text'            =>    'See More',
                'callback_data'   =>   $paid_history->current_page+1,
            ]));
            Chat::createQuestion('paid_report', 'see_more');
            Chat::sendTextMessageWithInlineKeyboard($text, $keyboard);
        }
    }

    private function curlexecution($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch,CURLOPT_POST,true);
        return curl_exec($ch);
    }

}
