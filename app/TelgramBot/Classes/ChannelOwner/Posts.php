<?php


namespace App\TelgramBot\Classes\ChannelOwner;


use App\TelgramBot\Common\Pages;

/**
 * Class Posts
 * @package App\TelgramBot\Classes\ChannelOwner
 */
class Posts
{
    /**
     *
     */
    public  function handle()
   {
       Pages::postsPage();
   }


}
