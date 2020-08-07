<?php


namespace App\TelgramBot\Classes;


class Help
{
    private $text_message;

    public function handle()
  {
      $this->makeMessage();
  }

    private function makeMessage()
    {
        $this->text_message = '<b>Registration:</b>'."\n".
        '<i>When You Start This Bot You Will See Two Buttons,Advertiser and Channel Owner</i>'."\n".
        'Advertiser Button Is For Persons Or Organization ,who wants to advert their content on available telegram channels'.
        'Channel Owner Is For Channel Owners In Order to Register their channel'.
        'Choose one you want and register.'."\n\n".
         '<b> Channel Owner Page </b>'."\n".
          'when you register as channel owner you will see the following buttons'."\n".
          '<b>Channel</b>'."\n".
          '<b>Payment Method</b>'."\n".
          '<b>Earning Report</b>'."\n".
          '<b>Paid Report</b>'.
          '<b>Help</b>'."\n".
          '<b>Pending Payment</b>'."\n";
    }
}
