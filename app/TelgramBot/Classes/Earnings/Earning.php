<?php


namespace App\TelgramBot\Classes\Earnings;


use App\TelgramBot\Common\Pages;
use App\TelgramBot\Common\Registered;

class Earning extends Registered
{
   public function handle()
   {
       Pages::earningPage();
   }
}
