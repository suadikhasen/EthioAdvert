<?php

namespace App\Http\Controllers\Admin\Payments;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentMethodForAdvertiserRequest;
use App\Http\Requests\PaymentMethodForChannelOwners;
use App\listOfPaymentMethod;
use App\Services\Common\TelegramBot;
use App\TelgramBot\Database\Admin\BankAccountRepository;
use App\TelgramBot\Database\Admin\ListOfPaymentMethodRepository;
use App\TelgramBot\Database\BankRepository;
use App\TelgramBot\Database\UserPaymentRepositories;

class PaymentMethodController extends Controller
{
    public  function  forChannelOwner()
    {
       $payment_methods = BankAccountRepository::paymentMethods();
       $column_keys     = ['id','bank_name'];
       $page_tittle     = 'payment method for channel owners';
       return view('admin.channel_owner.payment_methods_for_channel_owner',compact(['payment_methods' ,'column_keys','page_tittle']));
    }

    public  function forAdvertiser()
    {
        $payment_methods = ListOfPaymentMethodRepository::paymentMethodForAdvertiser();
        $column_keys    = ['id','bank_name','account_holder_name','account_number'];
        $page_tittle    = 'payment method for advertisers';
        $column_headers = ['Id','Payment Method Name','Payment Method Holder Name','Payment Method Idetification Number'];
        return view('admin.advertiser.payment_methods_for_advertiser',
        compact(['payment_methods','column_keys','page_tittle','column_headers']));
    }

    public function addNewPaymentMethodForChannelOwnersPage()
    {
        return view('admin.channel_owner.add_new_payment_method_for_channel_owner');
    }

    public function addNewPaymentMethodsForChannelOwners(PaymentMethodForChannelOwners $request)
    {
        BankAccountRepository::createPaymentMethod($request);
        return back()->with('success_notification','payment method added successfully');
    }

    public function deleteChannelOwnersPaymentMethod($payment_method_id)
    {
        BankAccountRepository::deletePaymentMethod($payment_method_id);
        return back()->with('success_notification','payment method deleted successfully');
    }

    public function addNewPaymentMethodForAdvertiserspage()
    {
        return view('admin.advertiser.add_new_payment_method_for_advertiser');
    }

    public function saveNewPaymentMethodsForAdvertiser(PaymentMethodForAdvertiserRequest $request)
    {
        ListOfPaymentMethodRepository::createPaymentMethodForAdvertisr($request);
        return back()->with('success_notification','payment method added successfully');
    }

  


}
