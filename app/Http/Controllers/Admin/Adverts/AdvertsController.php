<?php

namespace App\Http\Controllers\Admin\Adverts;

use App\Http\Controllers\Controller;
use App\TelgramBot\Database\Admin\AdvertRepository;
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
        return view('admin.adverts.detail_about_adverts',[
             'advert'  => $advert,
             ]);
    }


}
