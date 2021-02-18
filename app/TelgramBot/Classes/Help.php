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
        $how_to_register                        =         $this->makeHowToRegisterMessage();
        $how_can_i_add_channel                  =         $this->makeHowCanIAddChannelMessage();
        $how_can_know_my_earnig                 =         $this->makeHowCanIKnowMyEarning();
        $how_can_i_improve_my_channel_level     =         $this->makeHowCanIImproveMyChannelLevel();
        $how_can_i_add_my_payment_method        =         $this->howCanIAddPaymentMethod();
        $how_can_i_paid_my_earning              =         $this->howCanIPaidMyEarning();


    }

    private function makeHowToRegisterMessage()
    {
        return '✅  <b> How Can I Register ? </b>'."\n".
               'when you click start button you will get two options Advertiser And Channel Owner.'."\n".
               'if you are advertiser click Advertiser Button and if you are channel owner click Channel Owner Button'."\n".
               'next the bot will ask your full name ,send your full name'."\n".
               'last the bot will ask your phone number ,click the button that come with the question to send your phone number';
    }

    private function makeHowCanIAddChannelMessage()
    {
        return '✅ <b> How Can I add Channel ? </b>'."\n".
               'first,click Channels Button In Your Home Page'."\n".
               'secondl,click Add Channel Button '."\n".
               'third,the bot asks your channel username,send your channel username dont forget "@"'."\n".
               'if your channel fullfill our conditions the bot will send successfull message'."\n".
               'add the bot as admin in your channel '."\n".
               'when we approve the channel ,you will recieve a notification'."\n\n"; 
    }

    private function makeHowCanIKnowMyEarning()
    {
        return '✅ <b> How Can I Know How Much I earn ? (for channel owners) </b>'."\n".
               'first,click Earning Report On Your Home Page'."\n".
               'second,you will get three options total earning,monthly earning,today earning'."\n".
               'click those option buttons to know your earning'."\n\n";
    }

    private function makeHowCanIImproveMyChannelLevel()
    {
        return '✅ <b> How Can I Improve My Channel Level ? (for channel owners)</b>'."\n".
                '1.increase your number of active members'."\n".
                '2.make high quality posts on your channel'."\n\n";
    }

    private function howCanIPaidMyEarning()
    {
        return '✅ <b> When I will Paid My Earnings ? ( for channel owners ) </b>'."\n".
               'you will recieve your previous month earning  on 10 - 12 of a current month'."\n\n";
               
    }

    private function howCanIAddPaymentMethod()
    {
        return '✅ <b> How Can I Add Payment Method ? (for channel owners )</b>'."\n".
                'firts,click Payment Method On Your Home Page'."\n";
                 
    }


}
