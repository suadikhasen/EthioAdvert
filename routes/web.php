<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::name('admin.')->prefix('admin')->group( function () {
    Route::get('/','Admin\IndexController@index');
    Route::get('/list_of_channels','Admin\ChannelsController@listOfChannels');
    Route::get('/detail_about_advert/{id}','Admin\ChannelsController@viewMore')->name('detail_about_advert');

    Route::prefix('channels')->name('channels.')->group(function(){
      
        Route::get('/block_channels/{id}','Admin\Channel\BlockController@blockChannel')->name('block_channel');
        Route::get('/un_block_channels/{id}','Admin\Channel\BlockController@unBlockChannel')->name('unblock_channel');
        Route::get('/channels_advert/{channel_id}','Admin\Adverts\AdvertsController@viewChannelAdverts')->name('view_channels_advert');
        Route::get('/update_channel_information/{channel_id}','Admin\Channel\UpdateInformationOfChannel@updateBasicInformation')->name('update_information');
        
    });

    Route::prefix('adverts')->name('adverts.')->group(function(){
       
        Route::get('/list_of_adverts','Admin\Adverts\AdvertsController@listOfAdverts')->name('list_of_adverts');
        Route::get('/detail_about_advert/{advert_id}','Admin\Adverts\AdvertsController@detailAboutAdverts')->name('detail_about_advrt');
        Route::get('/search_adverts','Admin\Adverts\SearchController@search')->name('search_adverts');
    });


    Route::prefix('advertiser')->name('advertiser.')->group(function(){
      
        Route::get('/list_of_advertiser','Admin\Advertiser\AdvertiserController@listOfAdvertiser')->name('list_of_advertiser');
        Route::get('/list_of_advertisers_advert/{advertiser_id}','Admin\Advertiser\AdvertiserController@advertisersAdvert')->name('list_of_advertisers_advert');
        Route::get('/list_of_payment_history/{advertiser_id}','Admin\Advertiser\AdvertiserController@advertisersPaymentHistory')->name('list_of_payment_history');
    });

    Route::prefix('channel_owners')->name('channel_owners.')->group(function(){
      
        Route::get('/','Admin\ChannelOwners\ChannelOwnersController@listOfChannelOwners')->name('list_of_channel_owners')->name('channel_owners');
        Route::get('/list_of_channel_owners_channel/{id}','Admin\ChannelOwners\ChannelOwnersController@listOfChannelOwnersChannel')->name('list_of_channel_owners_channel');
        Route::get('/channel_owners_payment_history/{id}','Admin\ChannelOwners\ChannelOwnersController@listOfPayment')->name('channel_owners_payment_history');
        Route::get('/pending_payments','Admin\PaymentController@pendingPayments')->name('pending_payments');
        Route::get('/payment_page/{user_id}/{pending_amount}','Admin\PaymentController@goToPayPage')->name('go_to_payment_page');
        Route::get('/pay_to_channel_owners/{user_id}/{pending_amount}','Admin\PaymentController@pay')->name('pay_to_channel_owners');    
    });
});
