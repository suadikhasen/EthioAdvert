<?php

namespace App\Http\Controllers\Admin\Advertiser;

use App\Http\Controllers\Controller;
use App\TelgramBot\Database\Admin\AdvertRepository;
use App\TelgramBot\Database\Admin\PaymentRepository;
use App\TelgramBot\Database\Admin\UserRepository;

class AdvertiserController extends Controller
{
    public function listOfAdvertiser()
    {
        $advertisers = UserRepository::listOfAdvertiser();
        return view('admin.advertiser.list_of_advertiser',['advertisers' => $advertisers]);
    }

    public function detailAboutAdvertiser($chat_id)
    {
        $advertiser = UserRepository::findAdvertiser($chat_id);
    }

    public function advertisersAdvert($advertiser_id)
    {
        $adverts = AdvertRepository::findAdvertOfAdvertiser($advertiser_id);
        return view('admin.adverts.list_of_adverts',['adverts' => $adverts]);
    }

    public function advertisersPaymentHistory($advertiser_id)
    {
        $payment_histories = PaymentRepository::findAdvertisersPaymentHistory($advertiser_id);
        $advertiser  = UserRepository::findAdvertiser($advertiser_id);
        $total_payment = PaymentRepository::calculateTotalPaymentOfAdvertiser($advertiser_id);
        return view('admin.advertiser.payment_history',[
            'payment_histories' => $payment_histories,
            'total_payment' => $total_payment,
            'advertiser'   => $advertiser,
            ]);
    }


}
