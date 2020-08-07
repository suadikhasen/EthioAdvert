<?php


namespace App\TelgramBot\Classes;

use App\TelgramBot\Common\Pages;
use App\TelgramBot\Common\Registered;

class MainMenu extends Registered
{

   public function handle()
   {
       if (userType() === 'Advertiser'){
           Pages::advertiserPage();
       }else{
           Pages::channelOwnerPage();
       }

   }
}
