<?php

namespace App\Http\Controllers\TelegramBotApi;

use App\Http\Controllers\Controller;
use App\TelgramBot\Classes\Advertiser\AddPromotion;
use App\TelgramBot\Classes\Advertiser\DetailAdvertInfo;
use App\TelgramBot\Classes\Advertiser\EditAdvert;
use App\TelgramBot\Classes\Advertiser\EditAdvert\EditDate;
use App\TelgramBot\Classes\Advertiser\EditAdvert\EditDescription;
use App\TelgramBot\Classes\Advertiser\EditAdvert\EditMainContent;
use App\TelgramBot\Classes\Advertiser\EditAdvert\EditName;
use App\TelgramBot\Classes\Advertiser\EditAdvert\EditNumberOfView;
use App\TelgramBot\Classes\Advertiser\EditAdvert\EditPhoto;
use App\TelgramBot\Classes\Advertiser\MyPromotion;
use App\TelgramBot\Classes\Advertiser\ReOrderAdvert;
use App\TelgramBot\Classes\Advertiser\VerifyPayment;
use App\TelgramBot\Classes\Advertiser\ViewAdverts;
use App\TelgramBot\Classes\ChannelOwner\ChangePerDayPost;
use App\TelgramBot\Classes\ChannelOwner\PerDayPosts;
use App\TelgramBot\Classes\ChannelOwner\PostHistory;
use App\TelgramBot\Classes\ChannelOwner\Posts;
use App\TelgramBot\Classes\Channels\AddChannel;
use App\TelgramBot\Classes\ChannelOwner;
use App\TelgramBot\Classes\Channels\AllChannel;
use App\TelgramBot\Classes\Channels\RemoveChannel;
use App\TelgramBot\Classes\Earnings\MonthlyEarning;
use App\TelgramBot\Classes\Earnings\TodayEarning;
use App\TelgramBot\Classes\MainMenu;
use App\TelgramBot\Classes\Paid\PaidReport;
use App\TelgramBot\Classes\Paid\PendingPayment;
use App\TelgramBot\Classes\Payment\ChangeUserPaymentMethod;
use App\TelgramBot\Classes\Payment\UserPaymentMethod;
use App\TelgramBot\Commands\SkipCommand;
use App\TelgramBot\Commands\StartCommand;
use App\TelgramBot\Common\GeneralService;
use App\TelgramBot\Common\Pages;
use App\TelgramBot\Common\Services\Advertiser\EditAdvertService;
use App\TelgramBot\Common\Services\Advertiser\ReOrderAdvertService;
use App\TelgramBot\Common\Services\Advertiser\ViewAdvertService;
use App\TelgramBot\Common\Services\CacheService;
use App\TelgramBot\Common\Services\ChannelOwner\ChannelService;
use Illuminate\Support\Facades\Cache;
use Telegram\Bot\Api;
use App\Services\Command;
use App\TelgramBot\Classes\Advertiser;
use App\TelgramBot\Classes\Advertiser\AddPromotionClass\LevelOfChannel;
use App\TelgramBot\Classes\Advertiser\AddPromotionClass\TimeOfTheAdvertPerDay;
use App\TelgramBot\Classes\Advertiser\EditAdvert\Packages\EditNumberOfChannel;
use App\TelgramBot\Classes\Advertiser\EditAdvert\Packages\EditPackage;
use App\TelgramBot\Classes\Advertiser\EditAdvert\Packages\EditPostingTime;
use App\TelgramBot\Object\Chat;
use App\TelgramBot\Classes\Earnings\Earning;
use App\TelgramBot\Classes\Earnings\TotalEarning;
use App\TelgramBot\Common\Services\Advertiser\PromotionService;
use App\TelgramBot\Classes\Advertiser\EditAdvert\Packages\EditLevelOfChannel;
use App\TelgramBot\Classes\Advertiser\EditAdvert\Packages\EditNumberOfDays;
use Telegram\Bot\Exceptions\TelegramSDKException;

/**
 * Class IndexController
 * @package App\Http\Controllers\TelegramBotApi
 */
class IndexController extends Controller
{

    /**execute for every request
     * IndexController constructor.
     * @throws TelegramSDKException
     */
    public  function __construct()
    {
        Chat::$bot = (new Api());
        Chat::$bot->addCommands([
            StartCommand::class,
//            SkipCommand::class,
        ]);
        GeneralService::assignChatValues();
    }



    /**
     * @param Command $command
     * @throws TelegramSDKException
     */
    public function index(Command $command): void
    {
       if (isset(Chat::$isCommand)){
           exit;
       }else{
           $this->processInput();
       }

    }
    /**
     *
     * @throws TelegramSDKException
     */
    public function processInput(): void
    {
      if (Chat::$text_message === 'Advertiser'){
          Chat::deleteTemporaryData();
          (new Advertiser())->handle(true);
      }elseif (Chat::$text_message === 'Channel Owner'){
          Chat::deleteTemporaryData();
          (new ChannelOwner())->handle(true);
      }elseif(Chat::$text_message === 'Channel'){
          Chat::deleteTemporaryData();
          Pages::ChannelPage();
      }elseif (Chat::$text_message === 'Add Channel'){
          Chat::deleteTemporaryData();
          (new AddChannel())->handle(true);
      }elseif (Chat::$text_message === 'Main Menu'){
          Chat::deleteTemporaryData();
          (new MainMenu)->handle();
      }elseif (Chat::$text_message === 'Earning Report'){
          Chat::deleteTemporaryData();
          (new Earning())->handle();
      }elseif (Chat::$text_message === 'Total Earning'){
          Chat::deleteTemporaryData();
          (new TotalEarning())->handle(true);
      }elseif (Chat::$text_message === 'All Channel'){
          Chat::deleteTemporaryData();
          (new AllChannel())->handle(true);

      }elseif (Chat::$text_message === 'Payment Method'){
          Chat::deleteTemporaryData();
          (new UserPaymentMethod())->handle(true);
      }elseif (Chat::$text_message === 'Change Payment Method'){
          Chat::deleteTemporaryData();
          (new ChangeUserPaymentMethod())->handle(true);
      }elseif (Chat::$text_message === 'Today Earning'){
          Chat::deleteTemporaryData();
          (new TodayEarning() )->handle();
      }elseif (Chat::$text_message === 'Paid Report'){
          Chat::deleteTemporaryData();
          (new PaidReport())->handle(true);
      }elseif (Chat::$text_message === 'Monthly Earning'){
          Chat::deleteTemporaryData();
          (new MonthlyEarning())->handle();
      }elseif (Chat::$text_message === 'Pending Payment')
      {
          Chat::deleteTemporaryData();
          (new PendingPayment())->handle(true);
      }elseif (Chat::$text_message === 'Remove Channel'){
          Chat::deleteTemporaryData();
          (new RemoveChannel())->handle(true);
      }elseif (Chat::$text_message === 'Add Promotion'){
          Chat::deleteTemporaryData();
          (new AddPromotion())->handle(true);
      }elseif (Chat::$text_message === 'Posts')
      {
          Chat::deleteTemporaryData();
          (new Posts())->handle();
      }elseif (Chat::$text_message === 'Per Day Post')
      {
          Chat::deleteTemporaryData();
          (new PerDayPosts())->handle(true);
      }elseif (Chat::$text_message === 'Post History')
      {
          Chat::deleteTemporaryData();
          (new PostHistory())->handle(1,false);
      }
      elseif (Chat::$text_message === 'My Promotions'){
          Chat::deleteTemporaryData();
          (new MyPromotion())->handle(true);
      }elseif (GeneralService::checkAdvertPage()){
          (new MyPromotion())->handle(false);
      }elseif (ViewAdvertService::checkCommandIsViewAdvertCommand(Chat::$text_message)){
          (new ViewAdverts(ViewAdvertService::getAdvertIdFromView(Chat::$text_message)))->sendListOfOptions();
      }elseif (ViewAdvertService::checkTheKeyboardIsViewFullAdvertInformation()){
          (new DetailAdvertInfo(ViewAdvertService::getIDFromViewKeyboard()))->sendDetailInfo();
      }elseif (ViewAdvertService::checkIfTheKeyIsBackToViewAdvert()){
          (new ViewAdverts(ViewAdvertService::getIDFromViewKeyboard()))->sendListOfOptions(true);
      }elseif (EditAdvertService::checkEditAdvertPage()){

          (new EditAdvert(ViewAdvertService::getIDFromViewKeyboard()))->sendEditOptions();
      }elseif (EditAdvertService::checkCallBackQueryIsEditName()){
          Chat::deleteTemporaryData();
          (new EditName(ViewAdvertService::getIDFromViewKeyboard()))->sendEditNameMessage();
      }elseif (EditAdvertService::checkCallBackQueryIsEditDescription())
      {   Chat::deleteTemporaryData();
          (new EditDescription(ViewAdvertService::getIDFromViewKeyboard()))->sendEditDescriptionMessage();
      }elseif (EditAdvertService::checkCallBackQueryIsEditMainMessage()){
           Chat::deleteTemporaryData();
          (new EditMainContent(ViewAdvertService::getIDFromViewKeyboard()))->sendEditMainContentMessage();
      }elseif (EditAdvertService::checkCallBackQueryIsEditDate()){
           Chat::deleteTemporaryData();
          (new EditDate(ViewAdvertService::getIDFromViewKeyboard(),true))->handle();
      }elseif(EditAdvertService::checkCallBackQueryIsEditPackage()){
        Chat::deleteTemporaryData();
        (new EditPackage(ViewAdvertService::getIDFromViewKeyboard()))->sendEditOptions();
      }
      elseif (EditAdvertService::checkCallBackQueryIsEditPhoto()){
         Chat::deleteTemporaryData();
          (new EditPhoto(ViewAdvertService::getIDFromViewKeyboard()))->handle(true);
      }elseif (ChannelService::checkIfCallBackQueryIsPerDayPost()){
          (new PerDayPosts())->handle(false);
      }elseif (ChannelService::checkIfTheCommandISChangePerDayPost()){
          (new ChangePerDayPost(ChannelService::getChannelIdFromChangePerDayCommand(Chat::$text_message),Chat::$chat_id,true));
      }elseif (ChannelService::checkIfCallBackQueryIsPostHistory())
      {
          (new PostHistory())->handle(ViewAdvertService::getIDFromViewKeyboard(),true);
      }
      elseif (EditAdvertService::checkCallBackQueryIsEditNumberOfView()){
           Chat::deleteTemporaryData();
          (new EditNumberOfView(ViewAdvertService::getIDFromViewKeyboard()))->handle(true);
      } elseif (Chat::$text_message === 'Verify Payment'){
          Chat::deleteTemporaryData();
          (new VerifyPayment())->sendPaymentMethod();
      } elseif (ReOrderAdvertService::checkCallBackQueryIsReOrdered()){
         Chat::deleteTemporaryData();
          (new ReOrderAdvert(ViewAdvertService::getIDFromViewKeyboard()))->handle(true);
      } elseif(PromotionService::isLevelOfChannelKeyboard())
      {
         (new LevelOfChannel())->sendLevelofChannelMessage(ViewAdvertService::getIDFromViewKeyboard(),true);
      }elseif(PromotionService::isTimePageKeyboard())
      {
          (new TimeOfTheAdvertPerDay())->sendTimeMessage(1,ViewAdvertService::getIDFromViewKeyboard(),true);
      }elseif(EditAdvertService::checkInlineKeyboard('edit_level_Of_channel')){
         Chat::deleteTemporaryData();
         (new EditLevelOfChannel(ViewAdvertService::getIDFromViewKeyboard()))->sendMessage();
      }elseif(EditAdvertService::checkInlineKeyboard('edit_number_of_days')){
          Chat::deleteTemporaryData();
          (new EditNumberOfDays(ViewAdvertService::getIDFromViewKeyboard()))->sendEditMessage();
      }elseif(EditAdvertService::checkInlineKeyboard('edit_number_of_channel')){
          Chat::deleteTemporaryData();
          (new EditNumberOfChannel(ViewAdvertService::getIDFromViewKeyboard()))->sendEditMessage();
      }elseif(EditAdvertService::checkInlineKeyboard('edit_posting_time')){
          Chat::deleteTemporaryData();
          (new EditPostingTime(ViewAdvertService::getIDFromViewKeyboard()))->sendEditMessage(1,1,PromotionService::getCacheNumberOfDays(),PromotionService::getCacheLevelId(),false);
      }elseif(EditAdvertService::checkInlineKeyboard('level_keyboard_page')){
          (new EditLevelOfChannel(Cache::get('edit_advert'.Chat::$chat_id)))->sendMessage(ViewAdvertService::getIDFromViewKeyboard(),true);
      }elseif(EditAdvertService::checkInlineKeyboard('time_for_advert_page')){
          (new EditPostingTime(Cache::get('edit_advert'.Chat::$chat_id)))->sendEditMessage(1,ViewAdvertService::getIDFromViewKeyboard(),PromotionService::getCacheNumberOfDays(),PromotionService::getCacheLevelId(),true);
      }elseif(EditAdvertService::checkInlineKeyboard('select_advertiser_payment_method'))
      {
          (new VerifyPayment())->selectPaymentMethod();
      }
      else{
          $this->processWithTheirType(Chat::type());
      }
    }

    /**
     * @param $type
     * @throws TelegramSDKException
     */
    public function processWithTheirType($type): void
    {

       switch ($type){
           case 'Advertiser':
               (new Advertiser())->handle(false);
               break;
           case 'Channel Owner':
               (new ChannelOwner())->handle(false);
               break;
           case 'Total Earning':
               (new TotalEarning())->handle(false);
               break;
           case 'Add Channel':
               (new AddChannel())->handle(false);
               break;
           case 'payment_method':
               (new UserPaymentMethod())->handle(false);
               break;
           case 'change_payment_method':
               (new ChangeUserPaymentMethod())->handle(false);
               break;
           case 'paid_report':
               (new PaidReport())->handle(false);
               break;
           case 'All_Channel':
               (new AllChannel())->handle(false);
               break;
           case 'remove_channel':
               (new RemoveChannel())->handle(false);
               break;
           case 'Add_Promotion':
               (new AddPromotion())->handle(false);
               break;
           case 'Edit Name':
               (new EditName(Cache::get('edit_advert_id'.Chat::$chat_id)))->handle();
               break;
           case 'Edit Description':
               (new EditDescription(Cache::get('edit_advert_id'.Chat::$chat_id)))->handle();
               break;
           case 'Edit Main Content':
               (new EditMainContent(Cache::get('edit_advert_id'.Chat::$chat_id)))->handle();
               break;
           case 're_order_advert':
               (new ReOrderAdvert(Cache::get('re_order_advert_id'.Chat::$chat_id)))->handle(false);
               break;
           case 'Edit Date':
               (new EditDate(Cache::get('edit_advert_id'.Chat::$chat_id),false))->handle();
               break;
           case 'Edit Photo':
               (new EditPhoto(Cache::get('edit_advert_id'.Chat::$chat_id)))->handle(false);
                break;
           case 'Verify_Payment':
               (new VerifyPayment())->acceptRefNumber();
               break;
           case 'Edit_Number_Of_View':
               (new EditNumberOfView(Cache::get('edit_advert_id'.Chat::$chat_id)))->handle(false);
               break;
           case 'Change_Per_Day_Post':
               (new ChangePerDayPost(CacheService::getPerDayPostCache(Chat::$chat_id),Chat::$chat_id,false));
               break;
           case 'Edit_Number_Of_Channel':
               (new editNumberOfChannel(Cache::get('edit_advert_id'.Chat::$chat_id)))->editNumberOfChannel();
              break;   
           case 'Edit_Posting_Time':
            (new EditPostingTime(Cache::get('edit_advert_id'.Chat::$chat_id)))->edit();
              break;
           case 'Edit_Level_Of_Channel':
            (new EditLevelOfChannel(Cache::get('edit_advert_id'.Chat::$chat_id)))->editLevel();
             break;
           case 'Edit_Number_Of_Days':
            (new EditNumberOfDays(Cache::get('edit_advert_id'.Chat::$chat_id)));
              break;
           default:
            Chat::sendTextMessage('⚠️ unknown command or input ⚠️');   
       }

    }
}
