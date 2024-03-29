<?php

namespace App\Http\Controllers\Admin\Adverts;

use App\Http\Controllers\Controller;
use App\Services\Common\TelegramBot;
use App\TelegramPost;
use App\TelgramBot\Database\Admin\AdvertRepository;
use App\TelgramBot\Database\Admin\TelegramPostRepository;
use Illuminate\Http\Request;

class AdvertsController extends Controller
{
    public function viewChannelAdverts($channel_id)
    {
         $adverts = AdvertRepository::channelsAdvert($channel_id);
         $number_of_advert = AdvertRepository::countNumberOfAdvertInChannel($channel_id);
         $total_earning = AdvertRepository::totalEarning($channel_id);
         return view('admin.channels.channels_advert',['adverts'=>$adverts,'number_of_advert' => $number_of_advert,'total_earning' => $total_earning]);
    }

    public function listOfAdverts()
    {
        $adverts = AdvertRepository::allAdverts();
        $number_of_advert = AdvertRepository::countAdverts();
        
        return view('admin.adverts.list_of_adverts',
        [
         'adverts'  => $adverts,
         'number_of_advert'  => $number_of_advert,
        ]);

    }

    public function detailAboutAdverts($advert_id)
    {
        $advert = AdvertRepository::findAdvert($advert_id);
        $url=null;
        if($advert->image_path != null){
            $url = TelegramBot::getPhotoPath($advert);
        }
        return view('admin.adverts.detail_about_adverts',[
             'advert'  => $advert,
             'url'     => $url
             ]);
    }

    public function viewPostHistory($advert_id)
    {
         $telegram_posts_of_the_advert = TelegramPostRepository::listOfPostsOfAdvert($advert_id);
         $advert = AdvertRepository::findAdvert($advert_id);
         return view('admin.adverts.post_history',compact(['telegram_posts_of_the_advert','advert']));
    }

    public function approveAdvert($advert_id)
    {
      AdvertRepository::updateApproveStatus($advert_id,true);
      TelegramBot::sendAdvertApprovementNotification($advert_id);
      return back()->with('success_notification','advert approved successfully');
    }

    public function disApproveAdvert($advert_id)
    {
        AdvertRepository::updateApproveStatus($advert_id,false);
        return back()->with('success_notification','advert dis approved successfully');
    }

    
}
