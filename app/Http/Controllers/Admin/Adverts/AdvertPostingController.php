<?php

namespace App\Http\Controllers\Admin\Adverts;

use App\Http\Controllers\Controller;
use App\TelgramBot\Database\Admin\AdvertRepository;
use App\TelgramBot\Database\Admin\ChannelRepository;
use App\TelgramBot\Database\LevelOfChannelReopoksitory;
use App\TelgramBot\Database\PackageRepositoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class AdvertPostingController extends Controller
{
    private $advert;
    private $list_of_channels;
    private $filtered_list_of_channels;

    public function __construct($advert_id)
    {
        $this->advert = AdvertRepository::findAdvert($advert_id);

    }
    
    public function post()
     {
       if($this->checkPreRequestOfAdvert()){
          if($this->isNewPackage()){
             $this->processPosting();
          }
       }
     }

     private function processPosting()
     {
         $this->list_of_channels = ChannelRepository::listOfChannelsByChannelId($this->advert->level_id);
         $this->filtered_list_of_channels = $this->filterChannels();

     }

     private function filterChannels()
     {
         foreach($this->list_of_channels as $channels){
           if($channels)
         }
     }

     private function checkPreRequestOfAdvert()
     {
         if($this->advert->approve_status && $this->advert->payment_status && ($this->advert->active_status === 1 || $this->advert->active_status === 3))
           return true;

        return false;
     }

     private function isNewPackage()
     {
       if($this->advert->active_status === 1)
         return true;
       return false;
     }
}
