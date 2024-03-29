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

Route::middleware(['adminguest'])->group(function () {
    
    Route::get('/admin_login', 'Admin\Auth\LogInController@index')->name('login_page');
    Route::post('/login',   'Admin\Auth\LogInController@login')->name('login');
});


Route::middleware(['admin_auth'])->name('admin.')->prefix('admin')->group( function () {
    
    Route::get('/logout','Admin\Auth\LogInController@logOut')->name('logout');
    Route::get('/','Admin\IndexController@index')->name('home');
    Route::get('/list_of_channels','Admin\ChannelsController@listOfChannels');
    Route::get('/detail_about_advert/{id}','Admin\ChannelsController@viewMore')->name('detail_about_advert');
    Route::get('/profile','Admin\ProfileController@index');
    Route::get('/change_password','Admin\ProfileController@changePasswordPage')->name('change_password_page');
    Route::post('/change_password' , 'Admin\ProfileController@changePassword')->name('change_password');
    Route::get('/add_admin','Admin\ProfileController@addAdminPage')->name('add_admin_page');
    Route::post('/add_admin','Admin\ProfileController@addAdmin')->name('add_admin');

    Route::prefix('channels')->name('channels.')->group(function(){
      
        Route::get('/block_channels/{id}','Admin\Channel\BlockController@blockChannel')->name('block_channel');
        Route::get('/un_block_channels/{id}','Admin\Channel\BlockController@unBlockChannel')->name('unblock_channel');
        Route::get('/channels_advert/{channel_id}','Admin\Adverts\AdvertsController@viewChannelAdverts')->name('view_channels_advert');
        Route::get('/update_channel_information/{channel_id}','Admin\Channel\UpdateInformationOfChannel@updateBasicInformation')->name('update_information');
        Route::get('/assign_level/{channel_id}','Admin\Levels\levelAssignationController@assignLevel')->name('assign_level');
        Route::get('/add_qualty/{channel_id}','Admin\Levels\QualityController@addQualityPage')->name('add_quality');
        Route::post('/add_qualty/{channel_id}','Admin\Levels\QualityController@saveQuality')->name('save_quality');
        Route::get('/approve_channels/{channel_owner_id}/{channel_id}','Admin\Channels\ApproveController@approve')->name('approve_channel');
        Route::get('/dis_approve_channels/{channel_owner_id}/{channel_id}','Admin\Channels\DisApproveController@disApprove')->name('dis_approve_channel');

    });

    Route::prefix('adverts')->name('adverts.')->group(function(){
        
        Route::get('/list_of_adverts','Admin\Adverts\AdvertsController@listOfAdverts')->name('list_of_adverts');
        Route::get('/detail_about_advert/{advert_id}','Admin\Adverts\AdvertsController@detailAboutAdverts')->name('detail_about_advrt');
        Route::get('/search_adverts','Admin\Adverts\SearchController@search')->name('search_adverts');
        Route::get('/post_advert/{advert_id}','Admin\Adverts\AdvertPostingController@post')->name('post_the_advert');
        Route::get('/view_post_history/{advert_id}','Admin\Adverts\AdvertsController@viewPostHistory')->name('view_post_history');
        Route::get('/approve_advert/{advert_id}','Admin\Adverts\AdvertsController@approveAdvert')->name('approve_advert');
        Route::get('/dis_approve_advert/{advert_id}','Admin\Adverts\AdvertsController@disApproveAdvert')->name('dis_approve_advert');
        
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

    Route::prefix('levels')->name('levels.')->group(function(){
       
        Route::get('/','Admin\Levels\LevelController@allLevel')->name('list_of_levels');
        Route::get('/add_new_level','Admin\Levels\LevelController@addNewLevel')->name('add_new_level');
        Route::post('/save_level',  'Admin\Levels\LevelController@saveLevel')->name('save_level');
        Route::get('/list_of_channels_by_level/{level_id}','Admin\Levels\LevelController@seeLevelChannels')->name('list_of_channels_by_level');
        Route::get('/list_of_level_attributes' , 'Admin\Levels\LevelAttributeController@allAttributes')->name('list_of_level_attributes');
        Route::get('/edit_level_of_attributes/{id}','Admin\Levels\LevelAttributeController@editAttributePage')->name('edit_level_of_attributes'); 
        Route::get('/delete_level_of_attributes/{id}','Admin\Levels\LevelAttributeController@deleteAttribute')->name('delete_level_of_attributes'); 
        Route::get('/add_new_level_attribute','Admin\Levels\LevelAttributeController@addNewAttributePage')->name('add_new_level_attribute');
        Route::post('/save_new_level_attribute' ,'Admin\Levels\LevelAttributeController@saveNewAttribute')->name('save_new_level_attribute');
        Route::post('/save_edit_level_attribute/{id}' ,'Admin\Levels\LevelAttributeController@editAttribute')->name('save_edit_level_attribute');

    });


    Route::prefix('packages')->name('packages.')->group(function(){

        Route::get('/all_packages','Admin\Packages\PackagesController@all')->name('all_packages');
        Route::get('/add_new_package','Admin\Packages\PackagesController@addNew')->name('add_new_package');
        Route::post('/save_package' , 'Admin\Packages\PackagesController@save')->name('save_package');

    });
   Route::prefix('payments')->name('payments.')->group(function(){
        
     Route::get('/payment_methods_for_channel_owners','Admin\Payments\PaymentMethodController@forChannelOwner')->name('list_of_payment_methods_for_channel_owners');
     Route::get('/add_new_payment_method_for_channel_owners','Admin\Payments\PaymentMethodController@addNewPaymentMethodForChannelOwnersPage')->name('add_new_payment_method_for_channel_owners');
     Route::post('/save_new_payment_method_for_channel_owners','Admin\Payments\PaymentMethodController@addNewPaymentMethodsForChannelOwners')->name('save_new_payment_method_for_channel_owners');
     Route::get('/payment_methods_for_advertiser','Admin\Payments\PaymentMethodController@forAdvertiser')->name('list_of_payment_methods_for_advertiser');
     Route::get('/add_new_payment_method_for_advertiser','Admin\Payments\PaymentMethodController@addNewPaymentMethodForAdvertiserspage')->name('add_new_payment_method_for_advertiser');
     Route::post('/save_new_payment_method_for_advertiser','Admin\Payments\PaymentMethodController@saveNewPaymentMethodsForAdvertiser')->name('save_new_payment_method_for_advertiser');
     Route::get('/delete_payment_method_of_channel_owners/{payment_id}' , 'Admin\Payments\PaymentMethodController@deleteChannelOwnersPaymentMethod')->name('delete_payment_method_of_channel_owners');

   }); 

   Route::prefix('transaction_numbers')->name('transaction_numbers.')->group(function () {

       Route::get('/list_of_transactions','Admin\TransactionNumber\TransactionNumberController@listOfTransactionNumbers')->name('list_of_transactions');
       Route::get('/add_new_transaction_number','Admin\TransactionNumber\TransactionNumberController@addNewTransactionNumberPage')->name('add_new_transaction_number');
       Route::post('/add_new_transaction_number','Admin\TransactionNumber\TransactionNumberController@saveTransactionNumber')->name('save_transaction_number');
       Route::get('/edit_transaction_number/{id}','Admin\TransactionNumber\TransactionNumberController@editTransactionPage')->name('edit_transaction_page');
       Route::post('/edit_transaction_number/{id}','Admin\TransactionNumber\TransactionNumberController@editTransaction')->name('edit_transaction');
       Route::get('/delete_transaction/{transaction_id}','Admin\TransactionNumber\TransactionNumberController@deleteTransaction')->name('delete_transaction');
   });

   

});
